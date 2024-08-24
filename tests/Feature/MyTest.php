<?php

use App\Services\Catalog\CatalogService;
use Tests\TestCase;

class MyTest extends TestCase
{
    public function testMy()
    {
        /** @var CatalogService $service */
        $service = app(CatalogService::class);
        $r = $service->getGoodAttributes(1);
    }
}