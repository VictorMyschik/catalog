<?php

namespace Tests\Feature\WB;

use App\Jobs\Catalog\Wildberries\WBDownloadGoodsJob;
use App\Models\Catalog\Wildberries\WBCatalogGood;
use App\Models\Catalog\Wildberries\WBCatalogNotFound;
use App\Services\Catalog\Wildberries\WBImportService;
use Tests\TestCase;

class ImportWBCatalogTest extends TestCase
{
    public function testImportGroups()
    {
        WBDownloadGoodsJob::dispatch(446365574)->onConnection('sync');
    }

    public function testImport(): void
    {
        for ($i = 300200; $i <= 3000000; $i++) {
            if (WBCatalogGood::where('nm_id', $i)->exists() || WBCatalogNotFound::where('wb_id', $i)->exists()) {
                continue;
            }

            try {
                WBDownloadGoodsJob::dispatch(224713703)->onConnection('sync');
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
    }
}
