<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Catalog\Wildberries\ImageRepository;
use App\Repositories\Catalog\Wildberries\ImageRepositoryInterface;
use App\Repositories\Catalog\Wildberries\WBCatalogCacheRepository;
use App\Repositories\Catalog\Wildberries\WBCatalogInterface;
use App\Repositories\Catalog\Wildberries\WBGoodsCacheRepository;
use App\Repositories\Catalog\Wildberries\WBGoodsInterface;
use App\Repositories\Catalog\Wildberries\WBRepository;
use App\Services\Catalog\Wildberries\API\WBClient;
use App\Services\Catalog\Wildberries\ImageUploaderInterface;
use App\Services\Catalog\Wildberries\ImageUploadService;
use App\Services\Catalog\Wildberries\WBImportService;
use GuzzleHttp\Client;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

class WildberriesProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(WBClient::class)
            ->needs(LoggerInterface::class)
            ->give(function () {
                return Log::channel(env('LOG_CHANNEL'));
            });

        $this->app->bind(WBClient::class, function (Application $app) {
            return new WBClient(
                new Client(),
                $app->make(LoggerInterface::class),
                $app->make(Repository::class)->get('wildberries')
            );
        });

        $this->app->bind(WBCatalogInterface::class, function (Application $app) {
            return new WBCatalogCacheRepository(
                new WBRepository(
                    $app->make(DatabaseManager::class),
                ),
                $app->make(\Illuminate\Cache\Repository::class)
            );
        });

        $this->app->bind(WBGoodsInterface::class, function (Application $app) {
            return new WBGoodsCacheRepository(
                new WBRepository(
                    $app->make(DatabaseManager::class),
                ),
                $app->make(\Illuminate\Cache\Repository::class)
            );
        });

        // ImageRepositoryInterface
        $this->app->bind(ImageRepositoryInterface::class, ImageRepository::class);

        $this->app->bind(ImageUploaderInterface::class, function (Application $application) {
            $config = $application->make(\Illuminate\Config\Repository::class);

            return new ImageUploadService(
                $application->make(Factory::class)->disk($config->get('filesystems.default')),
                $application->make(ImageRepositoryInterface::class),
                $config->get('wildberries'),
            );
        });

        $this->app->bind(WBImportService::class, function (Application $app) {
            return new WBImportService(
                $app->make(WBClient::class),
                $app->make(WBGoodsInterface::class),
                $app->make(ImageUploaderInterface::class),
            );
        });

    }
}
