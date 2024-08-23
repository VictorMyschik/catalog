<?php

namespace App\Orchid\Providers;

use Illuminate\Support\ServiceProvider;
use Orchid\Icons\IconFinder;

class IconServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(IconFinder $iconFinder): void
    {
        $iconFinder->registerIconDirectory('fa', resource_path('icons/fontawesome'));
    }
}
