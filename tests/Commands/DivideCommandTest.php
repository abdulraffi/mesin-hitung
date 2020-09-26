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
        $number2 = $this->faker->randomFloat();
        $addCommand = new DivideCommand();
        $expected = $number1 * $number2;
        $this->assertEquals($expected, $addCommand->calculate($number1,$number2));
    }

    public function testErrorCalculate(){
        $this->faker = Faker\Factory::create();
        $number1 = $this->faker->randomFloat();
        $number2 = $this->faker->randomFloat();
        $addCommand = new DivideCommand();
        $expected = $number1 / 1;
        $this->assertNotEquals($expected, $addCommand->calculate($number1,$number2));
    }
}
