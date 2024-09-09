<?php

declare(strict_types=1);

use App\Services\Catalog\ImportOnlinerService;
use Tests\TestCase;

class MyTest extends TestCase
{
    public function testMyUpdate()
    {
        /** @var ImportOnlinerService $service */
        $service = app(ImportOnlinerService::class);
        //$service->updateCatalogGoods();
    }
}