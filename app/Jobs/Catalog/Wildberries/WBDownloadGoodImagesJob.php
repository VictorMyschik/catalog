<?php

declare(strict_types=1);

namespace App\Jobs\Catalog\Wildberries;

use App\Jobs\JobsEnum;
use App\Repositories\Catalog\Wildberries\WBGoodsCacheRepository;
use App\Services\Catalog\Wildberries\WBImportGoodService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class WBDownloadGoodImagesJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function __construct(public int $shopId, public int $goodId, public array $urls)
    {
        $this->queue = JobsEnum::UpdateCatalog->value;
    }

    public function handle(WBImportGoodService $service, WBGoodsCacheRepository $cacheRepository): void
    {
        $service->downloadGoodImage($this->goodId, $this->urls);

        $cacheRepository->clearCacheForShop($this->shopId);
    }
}
