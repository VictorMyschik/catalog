<?php

namespace App\Providers;

use App\Repositories\OnlinerDBRepository;
use App\Repositories\OnlinerRepositoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\ServiceProvider;

class CatalogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(OnlinerRepositoryInterface::class, function (Application $app) {
            return new OnlinerDBRepository(
                $app->make(DatabaseManager::class),
            );
        });
    }

    public function boot(): void {}
}
