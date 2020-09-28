<?php

namespace Jakmall\Recruitment\Calculator\Http\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Jakmall\Recruitment\Calculator\Connection;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;
use Jakmall\Recruitment\Calculator\Models\History;

class HistoryController
{

    private $historyManager;
    /**
     * @var string[]
     */
    private $delimiter;

    public function __construct(CommandHistoryManagerInterface $historyManager)
    {
        $this->delimiter = [
            'add' => ' + ',
            'subtract' => ' - ',
            'multiply' => ' * ',
            'divide' => ' / ',
            'pow' => ' ^ '
        ];
        $this->historyManager = $historyManager;
        new Connection();
    }

    public function index(Request $request)
    {
        $driver = $request->input('driver');
        if($driver)
            $history = $this->historyManager->findAll(null,$request->input('driver'));
        else
            $history = $this->historyManager->findAll(null,'database');

        $data = [];
        foreach ($history as $item) {
            $data[] = $this->responseStructure($item);
        }

        return Response::create($data,200);
    }

    public function show($id)
    {

    }

    public function remove($id)
    {

    }

    private function responseStructure($history){
        $command = strtolower($history['command']);
        $input = explode($this->delimiter[$command], $history['description']);
        return [
            'id' => 'id'.$history['no'],
            'command' => $command,
            'operation' => $history['description'],
            'input' => array_map('floatval', $input),
            'result' => (float)$history['result'],
            'time' => $history['created_at']
        ];
    }
}
