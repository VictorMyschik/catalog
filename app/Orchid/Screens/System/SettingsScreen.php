<?php

declare(strict_types=1);

namespace App\Orchid\Screens\System;

use App\Models\System\Settings;
use App\Orchid\Filters\System\SettingsFilter;
use App\Orchid\Layouts\System\Settings\SettingsEditLayout;
use App\Orchid\Layouts\System\Settings\SettingsListLayout;
use App\Repositories\System\SettingsRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class SettingsScreen extends Screen
{
    public function __construct(private readonly SettingsRepositoryInterface $repository) {}

    public function name(): string
    {
        return 'Settings';
    }

    public function description(): string
    {
        return 'Managing Site Settings';
    }

    public function query(): iterable
    {
        return [
            'list' => SettingsFilter::query(),
        ];
    }

    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Add')
                ->class('mr-btn-success')
                ->icon('plus')
                ->modal('setup_modal')
                ->modalTitle('Settings')
                ->method('saveSettings')
                ->asyncParameters(['id' => 0]),
        ];
    }

    public function layout(): iterable
    {
        return [
            SettingsFilter::displayFilterCard(),
            SettingsListLayout::class,
            Layout::modal('setup_modal', SettingsEditLayout::class)->async('asyncGetSettings'),
        ];
    }

    #region Popup From
    public function asyncGetSettings(int $id = 0): iterable
    {
        return ['setup' => Settings::loadBy($id) ?: new Settings()];
    }

    public function saveSettings(Request $request): void
    {
        $id = (int)$request->get('id');
        $setupIn = (array)$request->get('setup');

        $id = $this->repository->saveSetting($id, $setupIn);

        Toast::success('Saved with ID' . $id);
    }

    public function remove(Request $request): void
    {
        $setup = Settings::loadByOrDie((int)$request->get('id'));
        $setup->deleteMr();

        Toast::warning(__('Settings was removed'));
    }
    #endregion

    #region Filter
    public function runFiltering(Request $request): RedirectResponse
    {
        $list = [];
        foreach (SettingsFilter::getFilterFields() as $item) {
            if (!is_null($request->get($item))) {
                $list[$item] = $request->get($item);
            }
        }

        return redirect()->route('system.settings.list', $list);
    }

    public function clearFilter(): RedirectResponse
    {
        return redirect()->route('system.settings.list');
    }
    #endregion
}
