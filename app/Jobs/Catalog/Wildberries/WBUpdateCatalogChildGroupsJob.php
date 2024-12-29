<?php

declare(strict_types=1);

namespace App\Jobs\Catalog\Wildberries;

use App\Jobs\JobsEnum;
use App\Services\Catalog\Wildberries\WBImportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class WBUpdateCatalogChildGroupsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function __construct(public int $wbId)
    {
        $this->queue = JobsEnum::UpdateCatalog->value;
    }

    public function handle(WBImportService $service): void
    {
        $service->updateByGroup($this->wbId);
    }
}
