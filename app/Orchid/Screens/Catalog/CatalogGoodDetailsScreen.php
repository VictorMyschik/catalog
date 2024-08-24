<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Catalog;

use App\Models\Catalog\Good;
use App\Models\Catalog\Manufacturer;
use App\Orchid\Layouts\Lego\ActionDeleteModelLayout;
use App\Orchid\Layouts\Lego\InfoModalLayout;
use App\Services\Catalog\CatalogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Orchid\Screen\Actions\Button;
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
    public function __construct(private readonly CatalogService $service) {}

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
        return [
            Button::make('Сохранить')->class('mr-btn-success')->method('saveGood')->parameters(['id' => $this->good->id()]),
        ];
    }

    public function layout(): iterable
    {
        $out = [];

        $out[] = Layout::split([
            $this->getBaseLayout(),
            $this->getAdditionalLayout(),
        ]);

        $out[] = Layout::rows([$this->getImageLayout()]);

        $out[] = Layout::modal('view_good', InfoModalLayout::class)->async('asyncGetGood')->size(Modal::SIZE_XL);

        $out[] = Layout::rows([
            ActionDeleteModelLayout::getActionButtons('Удалить товар', 'deleteGood', ['id' => $this->good->id()]),
        ]);

        return $out;
    }

    private function getImageLayout(): ViewField
    {
        $images = $this->service->getGoodImages($this->good->id());

        foreach ($images as $image) {
            $image->btn = Group::make([
                Button::make('удалить')->class('mr-btn-danger')->icon('trash')->confirm('Удалить?')
                    ->method('deleteImage')->novalidate()
                    ->parameters(['image_id' => $image->id()]),
            ])->autoWidth()->render();
        }

        return ViewField::make('')->view('admin.good_images')->value($images);
    }

    private function getBaseLayout(): Rows
    {
        return Layout::rows([
            Input::make('good.name')->required()->title('Наименование'),
            Input::make('good.prefix')->title('Префикс'),
            Input::make('good.short_info')->title('Краткая информация'),
            TextArea::make('good.description')->name('good.description')->rows(5)->title('Описание'),
        ]);
    }

    public function asyncGetGood(int $id = 0): array
    {
        return [
            'body' => Good::loadByOrDie($id)->getSL(),
        ];
    }

    public function saveGood(Request $request, int $id): void
    {
        $input = Validator::make($request->all(), [
            'good.name'             => 'required',
            'good.prefix'           => 'nullable',
            'good.short_info'       => 'nullable',
            'good.description'      => 'nullable',
            'good.manufacturer_id'  => 'nullable|integer',
            'good.is_certification' => 'boolean',
        ])->validate()['good'];

        $this->service->saveGood($id, $input);
    }

    private function getAdditionalLayout(): Rows
    {
        return Layout::rows([
            Relation::make('good.manufacturer_id')->title('Производитель')->fromModel(Manufacturer::class, 'name'),
            Group::make([
                Label::make('good.link')->title('Ссылка на onliner.by'),

            ]),
            Group::make([
                Switcher::make('good.is_certification')->sendTrueOrFalse()->title('Требование сертификации'),
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

    public function deleteImage(int $image_id): void
    {
        $this->service->deleteImage($image_id);
    }

    public function deleteGood(int $id): RedirectResponse
    {
        $this->service->deleteGood($id);

        return redirect()->route('catalog.goods.list');
    }
}