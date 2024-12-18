<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Catalog\Wildberries;

use App\Models\Shop\Marketplace\Market;
use App\Models\Shop\Shop;
use App\Orchid\Filters\Marketplace\Wildberries\WBOrderFilter;
use App\Orchid\Layouts\Shop\Marketplace\Wildberries\WBOrderListLayout;
use App\Services\Wildberries\Import\Metrics\ImportStatisticService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class WBCatalogOrdersScreen extends Screen
{
    public ?Market $market = null;

    public function __construct(private readonly Request $request, private readonly ImportStatisticService $importStatisticService) {}

    public function name(): ?string
    {
        return $this->market->getShop()->getTitle();
    }

    public function description(): ?string
    {
        return 'Wildberries | заказы';
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Назад')->icon('arrow-left')->route('wb.market.details', ['marketId' => $this->market->id()]),
        ];
    }

    public function query(int $marketId): iterable
    {
        $this->market = Market::find($marketId);

        return [
            'order-list' => WBOrderFilter::runQuery(),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows($this->getActionLinkLayout()),
            WBOrderFilter::displayFilterCard($this->request),
            WBOrderListLayout::class,
        ];
    }

    public function getActionLinkLayout(): array
    {
        $out[] = Button::make('Загрузить заказы')
            ->class('mr-btn-success')
            ->method('downloadOrders')
            ->parameters(['marketId' => $this->market->id()])
            ->icon('download');

        return [Group::make($out)->autoWidth()];
    }

    public function downloadOrders(int $marketId): void
    {
        $this->importStatisticService->getOrders(Market::findOrFail($marketId), now()->subMonths(5), false);
    }

    #region Filter
    public function runFiltering(Request $request): RedirectResponse
    {
        $inputFilter = $request->all(WBOrderFilter::FIELDS);

        $list = ['marketId' => $this->market->id()];
        foreach (WBOrderFilter::FIELDS as $item) {
            if (!is_null($inputFilter[$item])) {
                $list[$item] = $inputFilter[$item];
            }
        }

        return redirect()->route('wb.market.orders', $list);
    }

    public function clearFilter(): RedirectResponse
    {
        return redirect()->route('wb.market.orders', ['marketId' => $this->market->id()]);
    }
    #endregion
}
