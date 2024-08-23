<?php

declare(strict_types=1);

namespace App\Orchid\Filters\Catalog;

use App\Models\Catalog\CatalogGroup;
use App\Models\Catalog\Good;
use App\Orchid\Layouts\Lego\ActionFilterPanel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\ViewField;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Facades\Layout;

class CatalogGoodsFilter extends Filter
{
    public const array FIELDS = [
        'name',
    ];

    public static function runQuery()
    {
        return Good::filters([self::class])->paginate(20);
    }

    public function run(Builder $builder): Builder
    {
        $input = $this->request->all(self::FIELDS);

        if (!empty($input['name'])) {
            $builder->where('name', 'LIKE', '%' . $input['name'] . '%');
        }

        return $builder;
    }

    public static function displayFilterCard(Request $request): Rows
    {
        $input = $request->all(self::FIELDS);

        $group = Group::make([
            Select::make('name')->value($input['name'])->title('Название'),
        ]);

        return Layout::rows([$group, ViewField::make('')->view('space'), ActionFilterPanel::getActionsButtons($request->all())]);
    }
}