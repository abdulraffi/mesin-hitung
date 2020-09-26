<?php

namespace Commands;

use Jakmall\Recruitment\Calculator\Commands\MultiplyCommand;
use PHPUnit\Framework\TestCase;
use Faker;

class MultiplyCommandTest extends TestCase
{

    protected $faker;

    public function testCalculate()
    {
        $this->faker = Faker\Factory::create();
        $number1 = $this->faker->randomFloat();
        $number2 = $this->faker->randomFloat();
        $addCommand = new MultiplyCommand();
        $expected = $number1 * $number2;
        $this->assertEquals($expected, $addCommand->calculate($number1,$number2));
    }

    public function testErrorCalculate(){
        $this->faker = Faker\Factory::create();
        $number1 = $this->faker->randomFloat();
        $number2 = $this->faker->randomFloat();
        $addCommand = new MultiplyCommand();
        $expected = $number1 * 2;
        $this->assertNotEquals($expected, $addCommand->calculate($number1,$number2));
    }
}
