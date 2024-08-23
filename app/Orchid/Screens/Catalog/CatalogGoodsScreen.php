<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Catalog;

use Orchid\Screen\Screen;

class CatalogGoodsScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'list' => null,
        ];
    }

    public function commandBar(): iterable
    {
        return [];
    }

    public function layout(): iterable
    {
        return [

        ];
    }
}