<?php

namespace Jakmall\Recruitment\Calculator\Commands;

use Illuminate\Console\Command;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;

abstract class CalculatorCommand extends Command
{
    /**
     * @var string
     */
    protected $signature;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $commandVerb;

    /**
     * @var string
     */
    protected $commandPassiveVerb;

    /**
     * @var string
     */
    protected $operator;

    /**
     * @param int|float $number1
     * @param int|float $number2
     *
     * @return int|float
     */
    abstract function calculate($number1, $number2);

    public function __construct()
    {
        $this->signature = $this->CommandSignature();
        $this->description = $this->CommandDescription();
        parent::__construct();
    }

    public function handle(CommandHistoryManagerInterface $historyManager): void
    {
        $numbers = $this->getInput();
        $description = $this->generateCalculationDescription($numbers);
        $result = $this->calculateAll($numbers);
        $output = sprintf('%s = %s', $description, $result);

        $historyManager->log([
            'command' => ucfirst($this->commandVerb),
            'description' => $description,
            'result' => $result,
            'output' => $output
        ]);

        $this->comment($output);
    }


    protected function CommandSignature(): string
    {
        return sprintf(
            '%s {numbers* : The numbers to be %s}',
            $this->commandVerb,
            $this->commandPassiveVerb
        );
    }

    protected function CommandDescription(): string
    {
        return sprintf('%s all given Numbers', ucfirst($this->commandVerb));
    }

    protected function generateCalculationDescription(array $inputs): string
    {
        $glue = sprintf(' %s ', $this->operator);
        return implode($glue, $inputs);
    }

    protected function getInput(): array
    {
        return $this->argument('numbers');
    }

    /**
     * @param array $numbers
     *
     * @return float|int
     */
    protected function calculateAll(array $numbers)
    {
        $number = array_pop($numbers);

        if (count($numbers) <= 0) {
            return $number;
        }

        return $this->calculate($this->calculateAll($numbers), $number);
    }
}
