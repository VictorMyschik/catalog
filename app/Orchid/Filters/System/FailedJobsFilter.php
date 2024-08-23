<?php

namespace App\Orchid\Filters\System;

use App\Enums\QueueNameEnum;
use App\Models\System\FailedJobs;
use App\Orchid\Layouts\Lego\ActionFilterPanel;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Facades\Layout;

class FailedJobsFilter extends Filter
{
    private static array $fields = [
        'payload',
        'queue',
    ];

    public function name(): string
    {
        return 'Setup';
    }

    public function parameters(): ?array
    {
        return self::$fields;
    }

    public function run(Builder $builder): Builder
    {
        $input = $this->request->all();

        if (isset($input['payload'])) {
            $value = htmlspecialchars((string)$input['payload'], ENT_QUOTES);

            if ($value !== '') {
                $builder->where(fn() => $builder->where('payload', 'LIKE', '%' . $value . '%'));
            }
        }

        if (isset($input['queue']) && isset(QueueNameEnum::getValues()[$input['queue']])) {
            $builder->where('queue', QueueNameEnum::getValues()[$input['queue']]);
        }

        return $builder;
    }

    public static function getFilterFields(): array
    {
        return self::$fields;
    }

    public static function query()
    {
        return FailedJobs::filters([self::class])->paginate(20);
    }

    public static function displayFilterCard(): Rows
    {
        return Layout::rows([
            Group::make([
                Input::make('payload')->maxlength(50)->value(request()->get('payload'))->title('Payload'),

                Select::make('queue')
                    ->options([null => 'all'] + QueueNameEnum::getValues())
                    ->value(is_null(request()->get('queue')) ? null : (int)request()->get('queue'))
                    ->title('Queue'),
            ]),

            ActionFilterPanel::getActionsButtons(),
        ]);
    }
}
