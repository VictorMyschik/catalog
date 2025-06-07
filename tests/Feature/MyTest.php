<?php

declare(strict_types=1);

use App\Jobs\Catalog\Onliner\SearchGoodsByCatalogGroupJob;
use App\Models\Catalog\Onliner\OnCatalogGroup;
use Tests\TestCase;

class MyTest extends TestCase
{
    public function testMyUpdate()
    {
        foreach(OnCatalogGroup::all() as $group) {
            SearchGoodsByCatalogGroupJob::dispatch($group);
        }
    }
}
