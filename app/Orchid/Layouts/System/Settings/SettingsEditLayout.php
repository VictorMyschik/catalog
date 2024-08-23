<?php

namespace App\Orchid\Layouts\System\Settings;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class SettingsEditLayout extends Rows
{
    public function fields(): array
    {
        return [
            Switcher::make('setup.active')
                ->sendTrueOrFalse()
                ->title('Active'),

            Input::make('setup.category')
                ->type('text')
                ->max(255)
                ->required()
                ->title('Category'),

            Input::make('setup.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title('Name'),

            Input::make('setup.value')
                ->type('text')
                ->required()
                ->title('Value'),

            Input::make('setup.code_key')
                ->type('text')
                ->required()
                ->title('Key in code'),

            TextArea::make('setup.description')
                ->rows(3)
                ->title('Description'),
        ];
    }
}
