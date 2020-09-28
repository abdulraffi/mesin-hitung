<?php


namespace Jakmall\Recruitment\Calculator\Commands;


class DivideCommand extends CalculatorCommand
{
    protected $commandVerb = 'divide';

    protected $commandPassiveVerb = 'divided';

    protected $operator = '/';

    /**
     * @inheritDoc
     */
    function calculate($number1, $number2)
    {
        return $number1 / $number2;
    }
}
