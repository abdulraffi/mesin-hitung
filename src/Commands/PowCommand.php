<?php


namespace Jakmall\Recruitment\Calculator\Commands;


class PowCommand extends CalculatorCommand
{
    protected $commandVerb = 'pow';

    protected $commandPassiveVerb = 'exponent';

    protected $operator = '^';

    protected function CommandSignature(): string
    {
        return sprintf(
            '%s {base : The base number} {exp : The exponent number}',
            $this->commandVerb
        );
    }

    protected function CommandDescription(): string
    {
        return sprintf('%s the given number', ucfirst($this->commandPassiveVerb));
    }

    protected function generateCalculationDescription(array $arguments): string
    {
        return sprintf('%s %s %s', $arguments['base'], $this->operator, $arguments['exponent']);
    }

    protected function getInput(): array
    {
        return [
            'base' => $this->argument('base'),
            'exponent' => $this->argument('exp')
        ];
    }

    protected function calculateAll(array $arguments)
    {
        $base = $arguments['base'];
        $exponent = $arguments['exponent'];

        return $this->calculate($base, $exponent);
    }

    /**
     * @inheritDoc
     */
    function calculate($number1, $number2)
    {
        return $number1 ** $number2;
    }
}
