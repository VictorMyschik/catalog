<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Catalog;

use App\Models\Catalog\CatalogType;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CatalogTypeListLayout extends Table
{
    protected $target = 'list';

    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID')->sort(),
            TD::make('name')->sort(),
            TD::make('json_link', 'Ссылка на Json данные')->sort(),

            TD::make('#', '#')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (CatalogType $catalogType) {
                    return DropDown::make()->icon('options-vertical')->list([
                        ModalToggle::make('изменить')
                            ->icon('pencil')
                            ->modal('type_modal')
                            ->modalTitle('Изменить тип')
                            ->method('saveCatalogType', ['type_id' => $catalogType->id()]),
                        Button::make('удалить')
                            ->icon('trash')
                            ->confirm('This item will be removed permanently.')
                            ->method('remove', [
                                'type_id' => $catalogType->id(),
                            ]),
                    ]);
                }),
        ];
    }
}