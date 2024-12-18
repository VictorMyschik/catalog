<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Catalog;

use App\Models\Catalog\OnCatalogGroup;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CatalogGroupListLayout extends Table
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
                ->render(function (OnCatalogGroup $catalogGroup) {
                    return DropDown::make()->icon('options-vertical')->list([
                        ModalToggle::make('изменить')
                            ->icon('pencil')
                            ->modal('type_modal')
                            ->modalTitle('Изменить тип')
                            ->method('saveCatalogType', ['group_id' => $catalogGroup->id()]),
                        Button::make('обновить товары')
                            ->icon('upload')
                            ->confirm('This item will be removed permanently.')
                            ->method('updateGoods', [
                                'group_id' => $catalogGroup->id(),
                            ]),
                        Button::make('удалить')
                            ->icon('trash')
                            ->confirm('This item will be removed permanently.')
                            ->method('remove', [
                                'group_id' => $catalogGroup->id(),
                            ]),
                    ]);
                }),
        ];
    }
}
