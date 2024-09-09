<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ESAddGoodEvent;
use App\Services\Elasticsearch\ESArticlesService;

final readonly class ESAddGoodListener
{
    public function __construct(private ESArticlesService $service) {}

    public function handle(ESAddGoodEvent $event): void
    {
        $this->service->addGood($event->good);
    }
}
