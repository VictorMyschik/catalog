<?php

declare(strict_types=1);

namespace App\Orchid\Filters\Catalog\Wildberries;

use App\Models\Catalog\Wildberries\WBCatalogGood;
use App\Models\Catalog\Wildberries\WBCatalogGroup;
use App\Models\Catalog\Wildberries\WBCatalogImage;
use App\Orchid\Layouts\Lego\ActionFilterPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Fields\ViewField;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Facades\Layout;

class WBCatalogGoodsFilter extends Filter
{
    public const array FIELDS = [
        'goodId',
        'title',
        'vendorCode',
        'nmId',
        'subjectId',
        'children',
    ];

    public static function runQuery()
    {
        return WBCatalogGood::filters([self::class])->paginate(20);
    }

    public function run(Builder $builder): Builder
    {
        $input = $this->request->all(self::FIELDS);

        $builder->join(WBCatalogGroup::getTableName(), WBCatalogGroup::getTableName() . '.id', '=', WBCatalogGood::getTableName() . '.subject_id');
        $builder->leftJoin(DB::raw('(SELECT MIN(original_name) as path, good_id FROM ' . WBCatalogImage::getTableName() . ' GROUP BY good_id) as images'), 'images.good_id', '=', WBCatalogGood::getTableName() . '.id');

        if (!empty($input['goodId'])) {
            $builder->where(WBCatalogGood::getTableName() . '.id', $input['goodId']);
        }
        if (!empty($input['nmId'])) {
            $builder->where(WBCatalogGood::getTableName() . '.nm_id', $input['nmId']);
        }

        if (!empty($input['title'])) {
            $builder->where(WBCatalogGood::getTableName() . '.title', 'like', '%' . $input['title'] . '%');
        }

        if (!empty($input['vendorCode'])) {
            $builder->whereRaw('LOWER(' . WBCatalogGood::getTableName() . '.vendor_code) LIKE ?', '%' . mb_strtolower($input['vendorCode']) . '%');
        }

        if (!empty($input['subjectId'])) {
            if ($input['children']) {
                $builder->where(function ($query) use ($input) {
                    $query->where(WBCatalogGroup::getTableName() . '.id', $input['subjectId'])
                        ->orWhere(WBCatalogGroup::getTableName() . '.parent_id', $input['subjectId']);
                });
            } else {
                $builder->where(WBCatalogGroup::getTableName() . '.id', $input['subjectId']);
            }
        }

        $select = [
            WBCatalogGood::getTableName() . '.*',
            'images.path as logo',
            WBCatalogGroup::getTableName() . '.name as group_name',
            WBCatalogGroup::getTableName() . '.id as subject_id',
        ];

        $builder->selectRaw(implode(',', $select));

        return $builder;
    }

    public static function displayFilterCard(Request $request): Rows
    {
        $input = $request->all(self::FIELDS);

        return Layout::rows([
            Group::make([
                Input::make('goodId')->value($input['goodId'])->type('number')->title('Внутренний ID'),
                Input::make('nmId')->value($input['nmId'])->type('number')->title('ID WB'),
                Input::make('vendorCode')->value($input['vendorCode'])->title('Артикул'),
                Input::make('title')->value($input['title'])->title('Наименование'),
                Select::make('subjectId')
                    ->title('Группа')
                    ->value($input['subjectId'])
                    ->empty('Все')
                    ->fromModel(WBCatalogGroup::class, 'name'),

                Switcher::make('children')
                    ->sendTrueOrFalse()
                    ->title('Учитывать вложенные группы')
                    ->value($input['children'] ?? false)
            ]),
            ViewField::make('')->view('space'),
            ActionFilterPanel::getActionsButtons()
        ])->title('Фильтр товаров');
    }
}