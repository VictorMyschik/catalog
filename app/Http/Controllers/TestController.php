<?php

namespace App\Http\Controllers;


use App\Jobs\TestJob;

class TestController extends Controller
{
    public function index(): void
    {
        TestJob::dispatch()->onConnection('rabbitmq');
    }
}
