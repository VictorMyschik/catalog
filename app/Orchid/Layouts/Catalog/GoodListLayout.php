<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Catalog;

use App\Models\Catalog\Good;
use App\Services\Catalog\CatalogService;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\ViewField;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class GoodListLayout extends Table
{
    protected $target = 'list';

    public function __construct(private readonly CatalogService $service) {}

    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID')->sort(),
            TD::make('', 'Logo')->render(function (Good $good) {
                return ViewField::make('')->view('admin.table_image')->value($this->service->getGoodLogo($good->id())?->getUrl());
            }),
            TD::make('manufacturer_id', 'Производитель')->render(function (Good $good) {
                return $this->service->getManufacturerName($good->manufacturer_id);
            })->sort(),
            TD::make('prefix', 'Префикс')->sort(),
            TD::make('name')->sort(),
            TD::make('string_id', 'Строковый ID')->sort(),
            TD::make('link', 'Ссылка')->render(function (Good $good) {
                return "<a href='{$good->link}' target='_blank'>link</a>";
            })->sort(),
            TD::make('Json')->render(function (Good $good) {
                return ModalToggle::make('')
                    ->icon('eye')
                    ->modalTitle('Json')
                    ->modal('view_good')
                    ->parameters(['id' => $good->id()]);
            })->sort(),

            TD::make('created_at', 'Created')
                ->render(fn(Good $good) => $good->created_at->format('d.m.Y'))
                ->sort()
                ->defaultHidden(),
            TD::make('updated_at', 'Updated')
                ->render(fn(Good $good) => $good->updated_at?->format('d.m.Y'))
                ->sort()
                ->defaultHidden(),

            TD::make('#', '#')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Good $good) {
                    return DropDown::make()->icon('options-vertical')->list([
                        Button::make('Delete')
                            ->icon('trash')
                            ->confirm('This item will be removed permanently.')
                            ->method('remove', [
                                'id' => $good->id(),
                            ]),
                    ]);
                }),
        ];
    }
}