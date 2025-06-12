<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\ClearCacheEvent;
use App\Events\ESAddGoodEvent;
use App\Listeners\ClearCacheListener;
use App\Listeners\ESAddGoodListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ESAddGoodEvent::class  => [ESAddGoodListener::class],
        ClearCacheEvent::class => [ClearCacheListener::class],
    ];

    public function boot(): void {}

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
