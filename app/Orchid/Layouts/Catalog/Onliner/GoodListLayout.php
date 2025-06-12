<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Catalog\Onliner;

use App\Models\Catalog\Onliner\OnCatalogGood;
use App\Services\Catalog\Onliner\OnlinerCatalogService;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\ViewField;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class GoodListLayout extends Table
{
    protected $target = 'list';

    public function __construct(private readonly OnlinerCatalogService $service) {}

    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID')->sort(),
            TD::make('active', 'Активно')->active()->sort(),
            TD::make('', 'Logo')->render(function (OnCatalogGood $good) {
                // TODO: пока не скачиваем картинки, показываем по прямой ссылке
                return ViewField::make('')->view('admin.table_image')->value($this->service->getGoodLogo($good->id())?->getOriginalUrl());
                //return ViewField::make('')->view('admin.table_image')->value($this->service->getGoodLogo($good->id())?->getUrl());
            }),
            TD::make('manufacturer_id', 'Производитель')->render(function (OnCatalogGood $good) {
                return $good->manufacturer_id ? $this->service->getManufacturerName($good->manufacturer_id) : null;
            })->sort(),
            TD::make('group_id', 'Тип')->render(function (OnCatalogGood $good) {
                return $this->service->getCatalogGroupById($good->group_id)->getName();
            })->sort(),
            TD::make('prefix', 'Префикс')->sort(),
            TD::make('name')->render(function (OnCatalogGood $good) {
                return '<a href="' . route('onliner.goods.details', ['id' => $good->id()]) . '" target="_blank">' . $good->getName() . '</a>';
            })->sort(),
            TD::make('string_id', 'Строковый ID')->sort(),
            TD::make('link', 'Ссылка')->render(function (OnCatalogGood $good) {
                return $good->link ? "<a href='{$good->link}' target='_blank'>link</a>" : null;
            })->sort(),
            TD::make('Json')->render(function (OnCatalogGood $good) {
                if (!$good->sl) {
                    return null;
                }

                return ModalToggle::make('')
                    ->icon('eye')
                    ->modalTitle('Json')
                    ->modal('view_good')
                    ->parameters(['id' => $good->id()]);
            })->sort(),

            TD::make('created_at', 'Created')
                ->render(fn(OnCatalogGood $good) => $good->created_at->format('d.m.Y'))
                ->sort()
                ->defaultHidden(),
            TD::make('updated_at', 'Updated')
                ->render(fn(OnCatalogGood $good) => $good->updated_at?->format('d.m.Y'))
                ->sort()
                ->defaultHidden(),

            TD::make('#', '#')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (OnCatalogGood $good) {
                    return DropDown::make()->icon('options-vertical')->list([
                        Button::make('удалить')
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
