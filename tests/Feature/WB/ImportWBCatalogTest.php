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
        /** @var WBImportService $service */
        $service = app(WBImportService::class);
        $service->loadGood(314380902);
    }

    public function testImport(): void
    {
        for ($i = 600000; $i <= 600200; $i++) {
            if (WBCatalogGood::where('nm_id', $i)->exists() || WBCatalogNotFound::where('wb_id', $i)->exists()) {
                continue;
            }

            try {
                WBDownloadGoodsJob::dispatch($i)->onConnection('sync');
            } catch (\Exception $e) {
                echo $e->getMessage();
            }

        }
    }
}
