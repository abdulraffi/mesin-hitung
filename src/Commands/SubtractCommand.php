<?php


namespace Jakmall\Recruitment\Calculator\Commands;


class SubtractCommand extends CalculatorCommand
{

    protected $commandVerb = 'subtract';

    protected $commandPassiveVerb = 'subtracted';

    protected $operator = '-';

    /**
     * @inheritDoc
     */
    function calculate($number1, $number2)
    {
        return $number1 - $number2;
    }
}
