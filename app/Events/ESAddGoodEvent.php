<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Catalog\Onliner\OnCatalogGood;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ESAddGoodEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public OnCatalogGood $good) {}
}
