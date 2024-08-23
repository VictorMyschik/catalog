<?php

namespace App\Orchid\Layouts\System\Cron;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class CronEditLayout extends Rows
{
  public function fields(): array
  {
    return [
      Switcher::make('cron.active')
        ->sendTrueOrFalse()
        ->title('Active'),

      Input::make('cron.name')
        ->type('text')
        ->max(50)
        ->title('Name')
        ->required(),

      Input::make('cron.period')
        ->type('number')
        ->min(0)
        ->max(9000000)
        ->title('Period in minutes')
        ->required(),

      Input::make('cron.cron_key')
        ->type('text')
        ->max(50)
        ->required()
        ->title('Key (use in code)'),

      TextArea::make('cron.description')->title('Description')->rows(5),
    ];
  }
}
