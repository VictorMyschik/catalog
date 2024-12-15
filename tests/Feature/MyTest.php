<?php

declare(strict_types=1);

use App\Jobs\Catalog\SearchGoodsByCatalogGroupJob;
use App\Models\Catalog\CatalogGroup;
use Tests\TestCase;

class MyTest extends TestCase
{
    public function testMyUpdate()
    {
        SearchGoodsByCatalogGroupJob::dispatch(CatalogGroup::loadByOrDie(1));
    }
}
