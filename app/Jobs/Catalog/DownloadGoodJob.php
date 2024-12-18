<?php

declare(strict_types=1);

namespace App\Jobs\Catalog;

use App\Jobs\JobsEnum;
use App\Models\Catalog\OnCatalogGroup;
use App\Services\Catalog\ImportOnlinerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Скачивание товаров с onliner.by
 */
class DownloadGoodJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public OnCatalogGroup $kind, public string $link)
    {
        $this->queue = JobsEnum::Update_catalog->value;
    }

    /**
     * Одна страница товаров (30 товаров)
     */
    public function handle(ImportOnlinerService $service): void
    {
        $service->downloadGoods($this->kind, $this->link);
    }
}
