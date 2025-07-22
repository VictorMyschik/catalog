<?php

namespace App\Http\Controllers;

use App\Jobs\Catalog\Wildberries\WBDownloadGoodsJob;
use App\Models\Catalog\Wildberries\WBCatalogGood;
use App\Models\Catalog\Wildberries\WBCatalogNotFound;
use Illuminate\View\View;

class TestController extends Controller
{
    public function index(): View
    {
        for ($i = 1000000; $i <= 1500000; $i++) {
            if (WBCatalogGood::where('nm_id', $i)->exists() || WBCatalogNotFound::where('wb_id', $i)->exists()) {
                continue;
            }

            try {
                WBDownloadGoodsJob::dispatch($i);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }

        return View('test');
    }
}
