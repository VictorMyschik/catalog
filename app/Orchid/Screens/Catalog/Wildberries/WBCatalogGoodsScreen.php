<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Catalog\Wildberries;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;

class WBCatalogGoodsScreen extends Screen
{
    public function __construct(
        private readonly Request $request,
    ) {}

    public function name(): ?string
    {
        return 'Товары Wildberries';
    }

    public function query(): iterable
    {
        return [
            'list' => WBCatalogGoodsFilter::runQuery(),
        ];
    }

    public function layout(): iterable
    {
        return [
            WBCatalogGoodsFilter::displayFilterCard($this->request),
            WBFullCatalogGoodsListLayout::class,
        ];
    }

    #region Filter
    public function runFiltering(Request $request): RedirectResponse
    {
        $inputFilter = $request->all(WBCatalogGoodsFilter::FILTER_FIELDS);

        $list = [];
        foreach (WBCatalogGoodsFilter::FILTER_FIELDS as $item) {
            if (!is_null($inputFilter[$item])) {
                $list[$item] = $inputFilter[$item];
            }
        }

        return redirect()->route('wb.catalog.goods.list', $list);
    }

    public function clearFilter(): RedirectResponse
    {
        return redirect()->route('wb.catalog.goods.list');
    }

    #endregion
}
