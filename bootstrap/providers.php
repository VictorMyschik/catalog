<?php

use App\Orchid\Providers\IconServiceProvider;
use App\Orchid\Providers\TableServiceProvider;
use App\Providers\CatalogServiceProvider;
use App\Providers\EventServiceProvider;
use App\Providers\SupervisorProvider;
use App\Providers\WildberriesProvider;

return [
    App\Providers\AppServiceProvider::class,
    WildberriesProvider::class,
    CatalogServiceProvider::class,
    TableServiceProvider::class,
    IconServiceProvider::class,
    EventServiceProvider::class,
    SupervisorProvider::class,
];
