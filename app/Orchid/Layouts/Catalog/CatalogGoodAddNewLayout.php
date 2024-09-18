<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Catalog;

use App\Services\Catalog\Enum\CatalogTypeEnum;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Layouts\Rows;

class CatalogGoodAddNewLayout extends Rows
{
    public function fields(): array
    {
        return [
            Group::make([
                Switcher::make('good.active')->sendTrueOrFalse()->title('Активно'),
                Select::make('good.type')
                    ->options(CatalogTypeEnum::getSelectList())
                    ->value(request()->get('group_id'))
                    ->required()
                    ->title('Тип каталога'),
            ]),
            Select::make('good.group_id')
                ->options($this->query->get('options', []))
                ->value(request()->get('group_id'))
                ->required()
                ->empty('Выберите группу')
                ->title('Группа'),

            Input::make('good.name')->type('text')->max(255)->required()->title('Наименование'),
        ];
    }
}
