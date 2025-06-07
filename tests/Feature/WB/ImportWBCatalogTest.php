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
        $service->updateCatalogGroups();
    }

    public function testImport(): void
    {
        for ($i = 700000; $i <= 800000; $i++) {
            if (WBCatalogGood::where('nm_id', $i)->exists() || WBCatalogNotFound::where('wb_id', $i)->exists()) {
                continue;
            }

            try {
                WBDownloadGoodsJob::dispatch($i);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }

        }
    }
}
