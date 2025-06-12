<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Catalog\Wildberries;

use App\Models\Catalog\Wildberries\WBCatalogGood;
use App\Orchid\Layouts\Lego\ActionDeleteModelLayout;
use App\Services\Catalog\Wildberries\WildberriesService;
use Illuminate\Http\RedirectResponse;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class WBCatalogGoodsDetailsScreen extends Screen
{
    public ?WBCatalogGood $good = null;

    public function __construct(private readonly WildberriesService $service) {}

    public function name(): string
    {
        return $this->good->getTitle();
    }

    public function description(): ?string
    {
        return View('admin.created_updated', ['model' => $this->good])->toHtml();
    }

    public function query(int $id): array
    {
        return [
            'good' => $this->good = $this->service->getGoodById($id),
        ];
    }

    public function commandBar(): array
    {
        return [
            Link::make('Назад')->icon('arrow-up')->route('wb.goods.list'),
        ];
    }

    public function layout(): iterable
    {
        $out[] = Layout::rows([
            ActionDeleteModelLayout::getActionButtons('Удалить товар', 'deleteGood'),
        ]);

        return $out;
    }

    public function deleteGood(int $id): RedirectResponse
    {
        $this->service->deleteGood($id);

        return redirect()->route('wb.goods.list');
    }
}
