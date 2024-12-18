<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Catalog\Wildberries;

use App\Models\Catalog\Wildberries\WBCountry;
use App\Orchid\Layouts\Catalog\Wildberries\WBCountiesListLayout;
use Orchid\Screen\Screen;

class WBCatalogCountriesScreen extends Screen
{
    public function name(): string
    {
        return 'Страны из справочника Wildberries';
    }

    public function query(): array
    {
        return [
            'list' => WBCountry::all(),
        ];
    }

    public function layout(): iterable
    {
        return [
            WBCountiesListLayout::class
        ];
    }
}
