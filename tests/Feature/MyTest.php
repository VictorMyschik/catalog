<?php

declare(strict_types=1);

use App\Models\Catalog\Onliner\OnCatalogGroup;
use App\Services\Catalog\Onliner\ImportOnlinerService;
use Tests\TestCase;

class MyTest extends TestCase
{
    public function testMyUpdate(): void
    {
        /** @var ImportOnlinerService $service */
        $service = app(ImportOnlinerService::class);
        $service->import('jet17r2700d8sd24', OnCatalogGroup::loadByOrDie(4), 'https://catalog.onliner.by/desktoppc/jets/jet17r2700d8sd24', true);
    }
}
