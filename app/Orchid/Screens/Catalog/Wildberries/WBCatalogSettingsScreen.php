<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Catalog\Wildberries;

use App\Models\Shop\Marketplace\Wildberries\WBSettings;
use Illuminate\Http\Request;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class WBCatalogSettingsScreen extends Screen
{
    public function name(): ?string
    {
        return 'Wildberries Settings';
    }

    public function description(): ?string
    {
        return 'Глобальные настройки работы с Wildberries';
    }

    public function query(): iterable
    {
        return [
            'list' => WBSettings::get()->all(),
        ];
    }

    public function layout(): iterable
    {
        return [
            WBGlobalSettingsListLayout::class,
            Layout::modal('setup_modal', WBSettingEditLayout::class)->async('asyncGetWBSettings')->size(Modal::SIZE_LG),
        ];
    }

    public function asyncGetWBSettings(int $setupId = 0): array
    {
        return ['setup' => WBSettings::findOrFail($setupId)];
    }

    public function saveSettings(Request $request, int $setupId): void
    {
        $setting = WBSettings::loadByOrDie($setupId);
        $setting->setValue($request->get('setup')['value']);
        $setting->save();
    }
}
