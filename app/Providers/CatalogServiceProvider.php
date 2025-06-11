<?php

namespace App\Providers;

use App\Repositories\Catalog\CatalogDBRepository;
use App\Repositories\Catalog\CatalogRepositoryInterface;
use App\Repositories\Images\ImageRepository;
use App\Repositories\Images\ImageRepositoryInterface;
use App\Services\Catalog\API\CatalogAPICache;
use App\Services\Catalog\API\CatalogAPIInterface;
use App\Services\Catalog\API\CatalogAPIResponse;
use App\Services\ImageUploader\ImageUploaderInterface;
use App\Services\ImageUploader\ImageUploadService;
use Elasticsearch\ClientBuilder;
use Illuminate\Cache\Repository;
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

        $this->app->bind(ImageUploaderInterface::class, function (Application $application) {
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

        // ESArticlesService
        $this->app->bind(\Elasticsearch\Client::class, function ($app) {
            $host = env('ELASTICSEARCH_HOST');
            $port = env('ELASTICSEARCH_PORT');
            $login = env('ELASTICSEARCH_LOGIN');
            $password = env('ELASTICSEARCH_PASSWORD');

            // HTTP Basic Authentication
            $hosts = [
                "{$login}:{$password}@{$host}:$port",
            ];
            return ClientBuilder::create()->setHosts($hosts)->build();
        });

        $this->app->bind(CatalogAPIInterface::class, function (Application $app) {
            return new CatalogAPICache(
                $app->make(CatalogAPIResponse::class),
                $app->make(Repository::class),
            );
        });

    }

    public function boot(): void {}
}
