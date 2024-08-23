<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Catalog;

use App\Models\Catalog\Good;
use App\Orchid\Filters\Catalog\CatalogGoodsFilter;
use App\Orchid\Layouts\Catalog\GoodListLayout;
use App\Orchid\Layouts\Lego\InfoModalLayout;
use App\Services\Catalog\CatalogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class CatalogGoodsScreen extends Screen
{
    protected $name = 'Список товаров';

    public function __construct(private Request $request, private readonly CatalogService $service) {}

    public function query(): iterable
    {
        return [
            'list' => CatalogGoodsFilter::runQuery(),
        ];
    }

    public function commandBar(): iterable
    {
        return [];
    }

    public function layout(): iterable
    {
        return [
            CatalogGoodsFilter::displayFilterCard($this->request),
            GoodListLayout::class,
            Layout::modal('view_good', InfoModalLayout::class)->async('asyncGetGood')->size(Modal::SIZE_XL),
        ];
    }

    public function asyncGetGood(int $id = 0): array
    {
        return [
            'body' => Good::loadByOrDie($id)->sl,
        ];
    }

    public function remove(int $id): void
    {
        $this->service->deleteGood($id);
    }

    #region Filter
    public function runFiltering(Request $request): RedirectResponse
    {
        $input = $request->all(CatalogGoodsFilter::FIELDS);

        $list = [];
        foreach (CatalogGoodsFilter::FIELDS as $item) {
            if (!is_null($input[$item])) {
                $list[$item] = $input[$item];
            }
        }

        return redirect()->route('catalog.goods.list', $list);
    }

    public function clearFilter(): RedirectResponse
    {
        return redirect()->route('catalog.goods.list');
    }
    #endregion
}