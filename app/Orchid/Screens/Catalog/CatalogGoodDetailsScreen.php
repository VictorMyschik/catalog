<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Catalog;

use App\Models\Catalog\Good;
use App\Models\Catalog\Manufacturer;
use App\Orchid\Layouts\Lego\InfoModalLayout;
use App\Services\Catalog\CatalogService;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\ViewField;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class CatalogGoodDetailsScreen extends Screen
{
    public function __construct(private Request $request, private readonly CatalogService $service) {}

    public ?Good $good = null;

    public function name(): ?string
    {
        return $this->good->getName();
    }

    public function description(): ?string
    {
        return View('admin.created_updated', ['model' => $this->good])->toHtml();
    }

    public function query(int $id): iterable
    {
        return [
            'good' => $this->good = $this->service->getGoodById($id),
        ];
    }

    public function commandBar(): iterable
    {
        return [];
    }

    public function layout(): iterable
    {
        $out = [];

        $out[] = Layout::split([
            $this->getBaseLayout(),
            $this->getAdditionalLayout(),
        ]);

        $out[] = Layout::modal('view_good', InfoModalLayout::class)->async('asyncGetGood')->size(Modal::SIZE_XL);

        return $out;
    }

    private function getBaseLayout(): Rows
    {
        return Layout::rows([
            Input::make('good.name')->title('Наименование'),
            Input::make('good.prefix')->title('Префикс'),
            Input::make('good.short_info')->title('Краткая информация'),
            TextArea::make('good.description')->name('good.description')->rows(5)->title('Описание'),
        ]);
    }

    public function asyncGetGood(int $id = 0): array
    {
        return [
            'body' => Good::loadByOrDie($id)->sl,
        ];
    }

    private function getAdditionalLayout()
    {
        return Layout::rows([
            Relation::make('good.manufacturer_id')->title('Производитель')->fromModel(Manufacturer::class, 'name'),
            Group::make([
                Label::make('good.link')->title('Ссылка на onliner.by'),

            ]),
            Group::make([
                Switcher::make('good.is_certification')->title('Требование сертификации'),
                Label::make('good.link')->title('Ссылка на onliner.by'),
            ]),
            Group::make([
                Label::make('')->title('Json данные при загрузке'),
                ModalToggle::make('Json')
                    ->modalTitle('Json')
                    ->modal('view_good')
                    ->parameters(['id' => $this->good->id()]),
            ])->autoWidth(),
            ViewField::make('')->view('hr'),
            Group::make([
                Label::make('good.string_id')->title('Строковый ID'),
                Label::make('good.int_id')->title('Числовой ID'),
            ]),
        ]);
    }
}