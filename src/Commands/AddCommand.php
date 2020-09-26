<?php


namespace Jakmall\Recruitment\Calculator\Commands;


class AddCommand extends CalculatorCommand
{
    protected $commandVerb = 'add';

    protected $commandPassiveVerb = 'added';

    protected $operator = '+';

    /**
     * @inheritDoc
     */
    function calculate($number1, $number2)
    {
        return $number1 + $number2;
    }
}
