<?php

declare(strict_types=1);

namespace App\Jobs\Catalog\Wildberries;

use App\Jobs\JobsEnum;
use App\Services\Catalog\Wildberries\WBImportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class WBDownloadGoodImagesJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function __construct(public int $goodId, public array $urls)
    {
        $this->queue = JobsEnum::WBCatalog->value;
    }

    public function handle(WBImportService $service): void
    {
        $service->downloadGoodImage($this->goodId, $this->urls);
    }
}
