<?php

namespace App\Jobs;

use App\Services\Catalog\ImportOnlinerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TestJob implements ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //$this->queue = JobsEnum::Update_catalog;
    }

    public function handle(ImportOnlinerService $service): void
    {
        echo 'TestJob';
    }
}