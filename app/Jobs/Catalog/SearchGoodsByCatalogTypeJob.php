<?php

declare(strict_types=1);

namespace App\Jobs\Catalog;

use App\Jobs\JobsEnum;
use App\Models\Catalog\CatalogType;
use App\Services\Catalog\ImportOnlinerService;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SearchGoodsByCatalogTypeJob
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public CatalogType $catalogType)
    {
        $this->queue = JobsEnum::Update_catalog;
    }

    public function handle(ImportOnlinerService $service): void
    {
        $service->searchNewGoodsByCatalogType($this->catalogType);
    }
}