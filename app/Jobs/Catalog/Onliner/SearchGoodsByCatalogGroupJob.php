<?php

declare(strict_types=1);

namespace App\Jobs\Catalog\Onliner;

use App\Jobs\JobsEnum;
use App\Models\Catalog\Onliner\OnCatalogGroup;
use App\Services\Catalog\Onliner\ImportOnlinerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SearchGoodsByCatalogGroupJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public OnCatalogGroup $catalogType)
    {
        $this->queue = JobsEnum::UpdateCatalog->value;
    }

    public function handle(ImportOnlinerService $service): void
    {
        $service->searchNewGoodsByCatalogGroup($this->catalogType);
    }
}
