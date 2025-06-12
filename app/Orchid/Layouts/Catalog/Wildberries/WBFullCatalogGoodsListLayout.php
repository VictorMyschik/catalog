<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Catalog\Wildberries;

use App\Models\Catalog\Onliner\OnCatalogGood;
use App\Models\Catalog\Wildberries\WBCatalogGood;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\ViewField;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class WBFullCatalogGoodsListLayout extends Table
{
    public $target = 'list';

    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID')->sort(),
            TD::make('nm_id', 'ID WB')->sort(),
            TD::make('logo')->render(function (WBCatalogGood $good) {
                return ViewField::make('')->view('admin.table_image')->value($good->getImage()?->getUrl());
            }),
            TD::make('group_name', 'Группа товаров')->render(function (WBCatalogGood $good) {
                return Link::make($good->getGroup()->getName())
                    ->route('wb.goods.list', array_merge(request()->query->all(), ['subjectId' => $good->getSubjectId()]));
            })->sort(),
            TD::make('title', 'Наименование')->sort(),
            TD::make('vendor_code', 'Артикул')->sort(),
            TD::make('', 'Ссылка на WB')->render(function (WBCatalogGood $good) {
                return Link::make('link')->target('_blank')->href('https://www.wildberries.ru/catalog/' . $good->getNmId() . '/detail.aspx');
            }),
            TD::make('Json')->render(function (WBCatalogGood $good) {
                if (!$good->sl) {
                    return null;
                }

                return ModalToggle::make('')
                    ->icon('eye')
                    ->modalTitle('Json')
                    ->modal('view_good')
                    ->parameters(['id' => $good->id()]);
            })->sort(),
            TD::make('created_at', 'Дата создания')->render(function ($model) {
                return $model->created_at->format('d.m.Y');
            })->defaultHidden()->sort(),
            TD::make('updated_at', 'Дата обновления')->render(function ($model) {
                return $model->updated_at?->format('h:i d.m.Y');
            })->sort(),
        ];
    }

    protected function hoverable(): bool
    {
        return true;
    }

    protected function compact(): bool
    {
        return true;
    }
}
