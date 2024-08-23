<?php

namespace App\Orchid\Layouts\Lego;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Support\Color;

class ActionFilterPanel
{
    public static function getActionsButtons(array $properties = []): Group
    {
        return Group::make([
            Button::make('Filter')->name('Поиск')->method('runFiltering', $properties)->class('btn btn-sm')->type(Color::INFO()),
            Button::make('Clear')->name('Очистить')->method('clearFilter')->class('btn btn-sm')->type(Color::SECONDARY()),
        ])->autoWidth();
    }
}
