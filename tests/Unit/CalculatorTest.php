<?php

namespace Tests\Unit;

use App\Calculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
   public function test_the_add_method(){
        $calculator = new Calculator();

        $result = $calculator->add(5,3);

        $this->assertEquals(8, $result);
   }

   public function test_the_subtract_method(){
        $calculator = new Calculator();

        $result = $calculator->subtract(5,3);

        $this->assertEquals(2,$result);
   }
}
