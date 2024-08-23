<?php

namespace App\Providers;

use App\Classes\Cache\CacheRedisClass;
use App\Repositories\System\SettingsRepository;
use App\Repositories\System\SettingsRepositoryCache;
use App\Repositories\System\SettingsRepositoryInterface;
use Illuminate\Cache\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CacheRedisClass::class, function (Application $app) {
            return new CacheRedisClass($app->make(Repository::class)->connection()->client());
        });

        // Settings Repository
        $this->app->bind(SettingsRepositoryInterface::class, function (Application $app) {
            return new SettingsRepositoryCache(
                new SettingsRepository($app->make(DatabaseManager::class)),
                $app->make(Repository::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
