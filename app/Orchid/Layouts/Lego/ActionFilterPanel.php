<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Lego;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Support\Color;

class ActionFilterPanel
{
    public static function getActionsButtons(array $properties = []): Group
    {
        return Group::make([
            Button::make('Filter')->name('Поиск')->method('runFiltering', $properties)->class('mr-btn-success'),
            Button::make('Clear')->name('Очистить')->method('clearFilter')->class('mr-btn-danger'),
        ])->autoWidth();
    }
}
