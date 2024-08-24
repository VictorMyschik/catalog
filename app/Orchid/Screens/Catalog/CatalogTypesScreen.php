<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Catalog;

use App\Models\Catalog\CatalogType;
use App\Orchid\Filters\Catalog\CatalogTypeFilter;
use App\Orchid\Layouts\Catalog\CatalogTypeEditLayout;
use App\Orchid\Layouts\Catalog\CatalogTypeListLayout;
use App\Services\Catalog\CatalogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class CatalogTypesScreen extends Screen
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
                ->method('saveCatalogType', ['type_id' => 0])
        ];
    }

    public function layout(): iterable
    {
        return [
            CatalogTypeFilter::displayFilterCard($this->request),
            CatalogTypeListLayout::class,
            Layout::modal('type_modal', CatalogTypeEditLayout::class)->async('asyncGetType'),
        ];
    }

    public function asyncGetType(int $type_id = 0): array
    {
        return [
            'type' => CatalogType::loadBy($type_id),
        ];
    }

    public function saveCatalogType(Request $request, int $type_id): void
    {
        $input = Validator::make($request->all(), [
            'type.name'      => 'required|string',
            'type.json_link' => 'required|string',
        ])->validate()['type'];

        $this->service->saveCatalogType($type_id, $input);
    }

    public function remove(int $type_id): void
    {
        $this->service->deleteCatalogType($type_id);
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
}