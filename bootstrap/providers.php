<?php

use App\Orchid\Providers\IconServiceProvider;
use App\Orchid\Providers\TableServiceProvider;
use App\Providers\CatalogServiceProvider;
use App\Providers\EventServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    CatalogServiceProvider::class,
    TableServiceProvider::class,
    IconServiceProvider::class,
    EventServiceProvider::class,
];
