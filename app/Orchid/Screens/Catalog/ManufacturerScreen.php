<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Catalog;

use App\Orchid\Filters\Catalog\CatalogGoodsFilter;
use App\Orchid\Filters\Catalog\ManufacturerFilter;
use App\Orchid\Layouts\Catalog\ManufacturerListLayout;
use App\Services\Catalog\CatalogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;

class ManufacturerScreen extends Screen
{
    protected $name = 'Список производителей';

    public function __construct(private readonly Request $request, private readonly CatalogService $service) {}

    public function query(): iterable
    {
        return [
            'list' => ManufacturerFilter::runQuery(),
        ];
    }

    public function commandBar(): iterable
    {
        return [];
    }

    public function layout(): iterable
    {
        return [
            ManufacturerFilter::displayFilterCard($this->request),
            ManufacturerListLayout::class,
        ];
    }

    public function remove(int $manufacturer_id): void
    {
        $this->service->deleteManufacturer($manufacturer_id);
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