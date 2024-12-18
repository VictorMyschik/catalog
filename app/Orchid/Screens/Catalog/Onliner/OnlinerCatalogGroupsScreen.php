<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Catalog\Onliner;

use App\Jobs\Catalog\SearchGoodsByCatalogGroupJob;
use App\Models\Catalog\Onliner\OnCatalogGroup;
use App\Orchid\Filters\Catalog\CatalogTypeFilter;
use App\Orchid\Layouts\Catalog\Onliner\CatalogGroupEditLayout;
use App\Orchid\Layouts\Catalog\Onliner\CatalogGroupListLayout;
use App\Services\Catalog\CatalogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class OnlinerCatalogGroupsScreen extends Screen
{
    protected $name = 'Список товаров';

    public function __construct(private Request $request, private readonly CatalogService $service) {}

    public function query(): iterable
    {
        return [
            'list' => CatalogTypeFilter::runQuery(),
        ];
    }

    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('добавить')
                ->class('mr-btn-success')
                ->icon('plus')
                ->modal('type_modal')
                ->modalTitle('Создать новый тип')
                ->method('saveCatalogGroup', ['group_id' => 0])
        ];
    }

    public function layout(): iterable
    {
        return [
            CatalogTypeFilter::displayFilterCard($this->request),
            CatalogGroupListLayout::class,
            Layout::modal('type_modal', CatalogGroupEditLayout::class)->async('asyncGetType'),
        ];
    }

    public function asyncGetType(int $type_id = 0): array
    {
        return [
            'type' => OnCatalogGroup::loadBy($type_id),
        ];
    }

    public function saveCatalogGroup(Request $request, int $group_id): void
    {
        $input = Validator::make($request->all(), [
            'type.name'      => 'required|string',
            'type.json_link' => 'required|string',
        ])->validate()['type'];

        $this->service->saveCatalogGroup($group_id, $input);
    }

    public function remove(int $group_id): void
    {
        $this->service->deleteCatalogType($group_id);
    }

    #region Filter
    public function runFiltering(Request $request): RedirectResponse
    {
        $input = $request->all(CatalogTypeFilter::FIELDS);

        $list = [];
        foreach (CatalogTypeFilter::FIELDS as $item) {
            if (!is_null($input[$item])) {
                $list[$item] = $input[$item];
            }
        }

        return redirect()->route('catalog.type.list', $list);
    }

    public function clearFilter(): RedirectResponse
    {
        return redirect()->route('catalog.type.list');
    }

    #endregion

    public function updateGoods(int $group_id): void
    {
        SearchGoodsByCatalogGroupJob::dispatch(OnCatalogGroup::loadByOrDie($group_id));
    }
}
