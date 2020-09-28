<?php

namespace Commands;

use Jakmall\Recruitment\Calculator\Commands\DivideCommand;
use PHPUnit\Framework\TestCase;
use Faker;

class DivideCommandTest extends TestCase
{
    protected $faker;

    public function testCalculate()
    {
        $this->faker = Faker\Factory::create();
        $number1 = $this->faker->randomFloat();
        $number2 = $this->faker->randomFloat(null, 1);
        $command = new DivideCommand();
        $expected = $number1 / $number2;
        $this->assertEquals($expected, $command->calculate($number1,$number2));
    }

}
