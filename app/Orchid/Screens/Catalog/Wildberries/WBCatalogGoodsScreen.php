<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Catalog\Wildberries;

use App\Models\Catalog\Wildberries\WBCatalogGood;
use App\Orchid\Filters\Catalog\Wildberries\WBCatalogGoodsFilter;
use App\Orchid\Layouts\Catalog\Wildberries\WBFullCatalogGoodsListLayout;
use App\Orchid\Layouts\Lego\InfoModalLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

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

            Layout::modal('view_good', InfoModalLayout::class)->async('asyncGetGood')->size(Modal::SIZE_XL),
        ];
    }

    public function asyncGetGood(int $id = 0): array
    {
        $json = WBCatalogGood::loadByOrDie($id)->sl;

        return ['body' => json_decode($json, true)];
    }

    #region Filter
    public function runFiltering(Request $request): RedirectResponse
    {
        $inputFilter = $request->all(WBCatalogGoodsFilter::FIELDS);

        $list = [];
        foreach (WBCatalogGoodsFilter::FIELDS as $item) {
            if (!is_null($inputFilter[$item])) {
                $list[$item] = $inputFilter[$item];
            }
        }

        return redirect()->route('wb.goods.list', $list);
    }

    public function clearFilter(): RedirectResponse
    {
        return redirect()->route('wb.goods.list');
    }

    #endregion
}
