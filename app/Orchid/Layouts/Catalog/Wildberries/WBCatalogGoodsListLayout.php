<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Catalog\Wildberries;

use App\Models\Shop\Marketplace\Wildberries\WBCatalogGood;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class WBCatalogGoodsListLayout extends Table
{
    public $target = 'good-list';

    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID')->sort(),
            TD::make('logo')->render(function (WBCatalogGood $good) {
                return View('admin.image')->with(['path' => $good->getImage()?->getUrl()]);
            }),
            TD::make('group_name', 'Группа товаров')->render(function (WBCatalogGood $good) {
                return Link::make($good->getGroup()->getName())
                    ->route('wb.market.details', ['subjectId' => $good->getSubjectId(), 'marketId' => $good->getMarketId()]);
            })->sort(),
            TD::make('title', 'Наименование')->sort(),
            TD::make('vendor_code', 'Артикул')->sort(),
            TD::make('nm_id', 'ID WB')->sort(),
            TD::make('', 'Ссылка на WB')->render(function (WBCatalogGood $good) {
                return Link::make('link')->target('_blank')->href('https://www.wildberries.ru/catalog/' . $good->getNmId() . '/detail.aspx');
            }),
            TD::make('created_at', 'Дата создания')->render(function ($model) {
                return $model->created_at->format('d.m.Y');
            })->defaultHidden()->sort(),
            TD::make('updated_at', 'Дата обновления')->render(function ($model) {
                return $model->updated_at?->format('d.m.Y h:i');
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
