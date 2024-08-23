<?php

namespace App\Orchid\Layouts\Lego;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Support\Color;

class DeleteByFilterPanel
{
    public static function getActionsButtons(): Group
    {
        return Group::make([
            Button::make('Delete by filter')->name('удалить по фильтру')->method('deleteAllByFilter')->class('btn btn-sm')->type(Color::DANGER()),
        ])->autoWidth();
    }
}
