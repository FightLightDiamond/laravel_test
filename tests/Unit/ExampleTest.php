<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Cache;
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
        dump(app());
        $a = Cache::get('fff');
//        $this->assertTrue(true);
    }
//
//    public function test_divide_by_zero()
//    {
////        profiler_start('my time metric name');
//
//// my code to track execution time
//
//
//        $math = new Math();
//        $result = $math->divide(10, 0);
//        $this->assertEquals(INF, $result);
////        profiler_finish('my time metric name');
//    }
//
//    public function test_not_item_array()
//    {
//       $arr = [1, 2];
//       $this->assertEquals(1, $arr[4]);
//    }
}
