<?php

namespace App\Providers;

use App\Repositories\Images\ImageRepository;
use App\Repositories\OnlinerDBRepository;
use App\Repositories\OnlinerRepositoryInterface;
use App\Services\ImageUploader\ImageNameResolver;
use App\Services\ImageUploader\ImageUploadService;
use Illuminate\Contracts\Filesystem\Factory;
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

        $this->app->bind(ImageUploadService::class, function (Application $application) {
            $config = $application->make(\Illuminate\Config\Repository::class);

            return new ImageUploadService(
                $application->make(Factory::class)->disk($config->get('filesystems.default')),
                new ImageNameResolver(),
                $application->make(ImageRepository::class),
                $config->get('storage')
            );
        });
    }

    public function boot(): void {}
}
