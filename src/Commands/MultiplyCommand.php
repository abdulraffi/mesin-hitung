<?php


namespace Jakmall\Recruitment\Calculator\Commands;


class MultiplyCommand extends CalculatorCommand
{

    protected $commandVerb = 'multiply';

    protected $commandPassiveVerb = 'multiplied';

    protected $operator = '*';

    /**
     * @inheritDoc
     */
    function calculate($number1, $number2)
    {
        return $number1 * $number2;
    }
}
