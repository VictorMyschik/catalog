<?php

declare(strict_types=1);

namespace App\Orchid\Screens\System;

use App\Services\Elasticsearch\ESService;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;

class ElasticsearchScreen extends Screen
{
    public string $name = 'Elasticsearch';
    public string $description = 'Управление индексами и документами Elasticsearch';

    public function __construct(private ESService $service) {}

    public function commandBar(): iterable
    {
        return [
            Button::make('очистить все индексы')
                ->icon('trash')
                ->method('clearAllIndices')
                ->confirm('Вы уверены, что хотите очистить все индексы?')
        ];
    }

    public function query(): iterable
    {
        return [];
    }

    public function layout(): iterable
    {
        return [];
    }

    public function clearAllIndices(): void
    {
        $this->service->clearByIndex();
    }
}
