<?php

use App\Services\Catalog\Onliner\ImportOnlinerService;
use App\Services\System\CronService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    new CronService(
        app(ImportOnlinerService::class),
    )->runAllActive();
})->everyMinute();
