<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\CatalogImportController;
use App\Services\Catalog\CatalogService;
use App\Services\Catalog\ImportOnlinerService;
use App\Services\ImageUploader\ImageUploadService;
use Illuminate\Http\Request;
use Tests\TestCase;

class CatalogControllerTest extends TestCase
{
    public function testImportLink(): void
    {
        $controller = new CatalogImportController(
            app(ImportOnlinerService::class),
            app(CatalogService::class),
        );

        $request = new Request([
            'url'            => 'https://catalog.onliner.by/sdapi/catalog.api/search/cpu',
            'type_id'        => 7,
            'is_load_images' => true,
            'complex'        => true,
            'max'            => 10,
        ]);

        $controller->importLink($request);
    }

    public function testUploadImage(): void
    {
        $url = 'https://imgproxy.onliner.by/XMDxzn0V2S29WVFg93PP6_46M1AOeWpeRTl_2Bdyr2k/w:1400/h:1100/f:jpg/aHR0cHM6Ly9jb250/ZW50Lm9ubGluZXIu/YnkvY2F0YWxvZy9k/ZXZpY2UvbGFyZ2Uv/ZTA2OGI0ZTcwNmRi/ZmZlYTg0ODE2NTM2/YTIxNTFlNjQuanBl/Zw';
        /** @var ImageUploadService $service */
        $service = app(ImageUploadService::class);

        $service->uploadImage(1, $url);
    }
}