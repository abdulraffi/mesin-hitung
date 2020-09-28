<?php

namespace Jakmall\Recruitment\Calculator\Http\Controller;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;

class CalculatorController
{


    private $historyManager;
    /**
     * @var string[]
     */
    private $actions;
    /**
     * @var string[]
     */
    private $operators;

    public function __construct(CommandHistoryManagerInterface $historyManager)
    {
        $this->historyManager = $historyManager;
        $this->actions = ['add', 'subtract', 'multiply', 'divide', 'pow'];
        $this->operators = [
            $this->actions[0] => '+',
            $this->actions[1] => '-',
            $this->actions[2] => '*',
            $this->actions[3] => '/',
            $this->actions[4] => '^'
        ];
    }

    public function calculate(Request $request, $action)
    {
        if(!in_array($action, $this->actions)) {
            return Response::create([
                'message' => 'Invalid action'
            ], 400);
        }

        $inputs = $request->input('input');
        if(!is_array($inputs)) {
            return Response::create([
                'message' => 'Invalid input'
            ], 400);
        }

        $operation = $this->generateCalculationDescription($action, $inputs);
        $result = $this->calculateAll($action, $inputs);

        $this->historyManager->log([
            'command' => ucfirst($action),
            'description' => $operation,
            'result' => $result,
            'output' => sprintf('%s = %s', $operation, $result)
        ]);

        return Response::create([
            'command' => $action,
            'operation' => $operation,
            'result' => $result
        ], 201);

    }

    protected function generateCalculationDescription($action, $inputs): string
    {
        $glue = sprintf(' %s ', $this->operators[$action]);
        return implode($glue, $inputs);
    }

    protected function calculateAll($action, $inputs)
    {
        $number = array_pop($inputs);
        if (empty($inputs)) {
            return $number;
        }
        return $this->calculateAction($this->calculateAll($action, $inputs), $number, $action);
    }

    protected function calculateAction($number1, $number2, $action)
    {
        $result = 0;
        switch ($action) {
            case 'add':
                $result = $number1 + $number2;
                break;
            case 'subtract':
                $result = $number1 - $number2;
                break;
            case 'multiply':
                $result = $number1 * $number2;
                break;
            case 'divide':
                $result = $number1 / $number2;
                break;
            case 'pow':
                $result = $number1 ** $number2;
                break;
        }
        return $result;
    }
}
