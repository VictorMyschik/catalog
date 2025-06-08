<?php

declare(strict_types=1);

namespace App\Orchid\Filters\System;

use App\Models\System\Settings;
use App\Orchid\Layouts\Lego\ActionFilterPanel;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\ViewField;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Facades\Layout;

class SettingsFilter extends Filter
{
    public const array FIELDS = [
        'active',
        'category',
        'code_key',
        'name',
        'value',
    ];

    public function name(): string
    {
        return 'Settings';
    }

    public function run(Builder $builder): Builder
    {
        $input = $this->request->all();

        if (isset($input['name'])) {
            $value = htmlspecialchars((string)$input['name'], ENT_QUOTES);

            if ($value !== '') {
                $builder->where('name', 'LIKE', '%' . $value . '%');
            }
        }

        if (isset($input['code_key'])) {
            $value = htmlspecialchars((string)$input['code_key'], ENT_QUOTES);

            if ($value !== '') {
                $builder->where('code_key', 'LIKE', '%' . $value . '%');
            }
        }

        if (isset($input['value'])) {
            $value = htmlspecialchars((string)$input['value'], ENT_QUOTES);

            if ($value !== '') {
                $builder->where('value', 'LIKE', '%' . $value . '%');
            }
        }

        if (!is_null($input['active'] ?? null)) {
            $builder->where('active', (bool)$input['active']);
        }

        if ($input['category'] ?? null) {
            $result = array_intersect($input['category'], self::getCategoryList());

            if (count($result) !== 0) {
                $builder->whereIn('category', $result);
            }
        }

        return $builder;
    }

    public function query(): iterable
    {
        return Settings::filters([self::class])->paginate(20);
    }

    public static function displayFilterCard(): Rows
    {
        return Layout::rows([
            Group::make([
                Select::make('active')
                    ->options([null => 'all', 1 => 'active', 0 => 'not active'])
                    ->value(request()->get('active'))
                    ->title('Active'),

                Select::make('category')
                    ->options(self::getCategoryList())
                    ->multiple()
                    ->value(request()->get('category'))
                    ->title('Category'),

                Input::make('name')->value(request()->get('name'))->title('Name'),
                Input::make('code_key')->value(request()->get('code_key'))->title('Key (in code)'),
                Input::make('value')->value(request()->get('value'))->title('Value'),
            ]),

            ViewField::make('')->view('space'),

            ActionFilterPanel::getActionsButtons(),
        ]);
    }

    private static function getCategoryList(): array
    {
        $category = array_unique(array_column(Settings::getSettingList(), 'category'));

        return array_combine($category, $category);
    }
}
