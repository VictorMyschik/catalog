<?php

namespace App\Orchid\Layouts\Lego;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Support\Color;

class ActionDeleteModelLayout
{
    public static function getActionButtons(string $title, string $methodName = 'remove', array $parameters = []): Group
    {
        return Group::make([
            Button::make('Clear')->confirm('Удалить?')->class('btn btn-sm')->name($title)->method($methodName)->parameters($parameters)->type(Color::DANGER)->novalidate(),
        ])->autoWidth();
    }
}
