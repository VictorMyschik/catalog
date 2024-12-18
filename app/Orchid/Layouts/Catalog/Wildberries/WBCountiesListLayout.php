<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Catalog\Wildberries;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class WBCountiesListLayout extends Table
{
    protected $target = 'list';

    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID')->sort(),
            TD::make('wb_id', 'Wildberries ID')->sort(),
            TD::make('name', 'Наименование')->sort(),
            TD::make('full_name', 'Полное наименование')->sort(),
        ];
    }

    protected function compact(): bool
    {
        return true;
    }

}
