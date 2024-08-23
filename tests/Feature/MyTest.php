<?php

use App\Jobs\TestJob;
use Tests\TestCase;

class MyTest extends TestCase
{
    public function testMy()
    {
        for ($i = 0; $i < 10; $i++) {
            TestJob::dispatch();
        }
    }
}