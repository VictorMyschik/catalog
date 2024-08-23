<?php

namespace App\Providers;

use App\Repositories\Catalog\CatalogDBRepository;
use App\Repositories\Catalog\CatalogRepositoryInterface;
use App\Repositories\Images\ImageRepository;
use App\Repositories\Images\ImageRepositoryInterface;
use App\Services\ImageUploader\ImageUploadService;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\ServiceProvider;

class CatalogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CatalogRepositoryInterface::class, function (Application $app) {
            return new CatalogDBRepository(
                $app->make(DatabaseManager::class),
            );
        });

        $this->app->bind(ImageUploadService::class, function (Application $application) {
            $config = $application->make(\Illuminate\Config\Repository::class);

            return new ImageUploadService(
                $application->make(Factory::class)->disk($config->get('filesystems.default')),
                $application->make(ImageRepositoryInterface::class),
                $config->get('storage')
            );
        });

        $this->app->bind(ImageRepositoryInterface::class, function (Application $app) {
            return new ImageRepository($app->make(DatabaseManager::class));
        });
    }

    public function boot(): void {}
}
