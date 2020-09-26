<?php

namespace Commands;

use Jakmall\Recruitment\Calculator\Commands\PowCommand;
use PHPUnit\Framework\TestCase;
use Faker;

class PowCommandTest extends TestCase
{
    protected $faker;

    public function testCalculate()
    {
        $this->faker = Faker\Factory::create();
        $number1 = $this->faker->randomNumber();
        $number2 = $this->faker->randomNumber();
        $command = new PowCommand();
        $expected = $number1 ** $number2;
        $this->assertEquals($expected, $command->calculate($number1,$number2));
    }

}
