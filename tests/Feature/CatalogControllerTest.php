<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\CatalogController;
use App\Services\Onliner\ImportOnlinerService;
use App\Services\Onliner\OnlinerService;
use Illuminate\Http\Request;
use Tests\TestCase;

class CatalogControllerTest extends TestCase
{
    public function testImportLink(): void
    {
        $controller = new CatalogController(
            app(ImportOnlinerService::class),
            app(OnlinerService::class),
        );

        $request = new Request([
            'url'            => 'https://catalog.onliner.by/notebook/honor/5301ahgw',
            'type_id'        => 1,
            'is_load_images' => false,
            'complex'        => false,
        ]);

        $controller->importLink($request);
    }
}