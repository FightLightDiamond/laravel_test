<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class Math {
    public function divide($a, $b) {
        return $a / $b;
    }
}

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_that_true_is_true()
    {
        $this->assertTrue(true);
    }

    public function test_divide_by_zero()
    {
        $math = new Math();
        $result = $math->divide(10, 0);
        $this->assertEquals(INF, $result);
    }

    public function test_not_item_array()
    {
       $arr = [1, 2];
       $this->assertEquals(1, $arr[4]);
    }
}
