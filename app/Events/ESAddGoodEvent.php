<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Catalog\Good;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ESAddGoodEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public Good $good) {}
}
