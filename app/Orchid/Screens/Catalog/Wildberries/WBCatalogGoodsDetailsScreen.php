<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Catalog\Wildberries;

use App\Models\Catalog\Wildberries\WBCatalogGood;
use App\Orchid\Layouts\Lego\ActionDeleteModelLayout;
use App\Orchid\Layouts\Lego\InfoModalLayout;
use App\Services\Catalog\Wildberries\WBImportService;
use App\Services\Catalog\Wildberries\WildberriesService;
use Illuminate\Http\RedirectResponse;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\ViewField;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class WBCatalogGoodsDetailsScreen extends Screen
{
    public ?WBCatalogGood $good = null;

    public function __construct(
        private readonly WildberriesService $service,
        private readonly WBImportService    $importService,
    ) {}

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
        $out[] = Layout::split([
            $this->getBaseLayout(),
            $this->getAdditionalLayout(),
        ]);

        $out[] = Layout::rows([
            ViewField::make('')->view('admin.html')->value($this->good->getDescription()),
        ]);

        $out[] = Layout::rows([
            $this->getImageBtnLayout(),
            $this->getImageLayout()
        ]);

        $out[] = Layout::rows($this->getAttributesLayout());

        $out[] = Layout::rows([
            ActionDeleteModelLayout::getActionButtons('Удалить товар', 'deleteGood'),
        ]);

        $out[] = Layout::modal('view_good', InfoModalLayout::class)->async('asyncGetGood')->size(Modal::SIZE_XL);

        return $out;
    }

    private function getBaseLayout(): Rows
    {
        return Layout::rows([
            Label::make('title')->title('Название товара')->value($this->good->getTitle()),
            Group::make([
                Label::make('id')->title('ID товара')->value($this->good->id()),
                Label::make('nm_id')->title('ID Wildberries товара')->value($this->good->getNmId()),
                Label::make('vendor_code')->title('Артикул')->value($this->good->vendor_code),
            ]),
        ]);
    }

    private function getAdditionalLayout(): Rows
    {
        return Layout::rows([
            Group::make([
                Label::make('')->value($this->good->getBrand()->getName())->title('Бренд'),
                Label::make('')->value($this->good->getGroup()->getName())->title('Группа'),
            ]),
            Group::make([
                Label::make('')->title('Json данные при импорте'),
                ModalToggle::make('Json')
                    ->modalTitle('Json')
                    ->modal('view_good')
                    ->parameters(['id' => $this->good->id()]),
            ])->autoWidth(),
            Link::make('link')->title('Ссылка на страницу Wildberries')->horizontal()->icon('link')->target('_blank')->href($this->good->getLink()),
        ]);
    }

    private function getAttributesLayout(): array
    {
        $list = $this->good->getJsonField('grouped_options');

        return [
            ViewField::make('')->view('admin.wildberries.good_attributes')->value($list),
        ];
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

    public function asyncGetGood(int $id = 0): array
    {
        return [
            'body' => WBCatalogGood::loadByOrDie($id)->getSL(),
        ];
    }

    private function getImageBtnLayout(): Group
    {
        return Group::make([
            Button::make('Перезагрузить фото')
                ->method('reUploadGoodPhotos')
                ->novalidate()
                ->class('mr-btn-success')
                ->confirm('Вы уверены, что хотите перезагрузить все картинки с сайта Onliner?'),

            Button::make('Удалить все фото')
                ->method('deleteAllGoodPhoto')
                ->novalidate()
                ->class('mr-btn-danger')
                ->confirm('Вы уверены, что хотите удалить все фото?')
                ->parameters(['good_id' => $this->good?->id()]),
        ])->autoWidth();
    }

    public function deleteGood(int $id): RedirectResponse
    {
        $this->service->deleteGood($id);

        return redirect()->route('wb.goods.list');
    }

    public function deleteImage(int $image_id): void
    {
        $this->service->deleteImage($image_id);
    }

    public function deleteAllGoodPhoto(int $good_id): void
    {
        $this->service->deleteAllGoodPhoto($good_id);
    }

    public function reUploadGoodPhotos(): void
    {
        $this->importService->reloadGoods($this->good);
    }
}
