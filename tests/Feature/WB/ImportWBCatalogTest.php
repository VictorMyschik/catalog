<?php

namespace Tests\Feature\WB;

use App\Models\Catalog\Wildberries\WBCatalogGroup;
use App\Services\Catalog\Wildberries\API\WBClient;
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
        $list = WBCatalogGroup::all();

        $client = app(WBClient::class);


        foreach ($list as $group) {
            $id = $group->id();
            $response = $client->getGoodByGroup($id);

            $result = $response['data']['total'] ?? null;

            if ($result) {
                WBCatalogGroup::where('id', $group->id())->update(['has_goods' => true]);
            }
        }
    }
}
