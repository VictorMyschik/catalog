<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;

class PlatformProvider extends OrchidServiceProvider
{
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);
    }

    /**
     * @return Menu[]
     */
    public function menu(): array
    {
        return [
            Menu::make('Onliner')->icon('info')->list([
                Menu::make('Товары')->icon('list')->route('onliner.goods.list'),
                Menu::make('Группы товаров')->icon('list')->route('onliner.type.list'),
                Menu::make('Производители')->icon('list')->route('onliner.manufacturer.list')->divider(),
            ])->divider(),
            Menu::make('Wildberries')->icon('info')->list([
                Menu::make('Товары')->icon('list')->route('wb.goods.list'),
                //Menu::make('Страны')->route('wb.countries.list'),
            ])->divider(),

            Menu::make('System')->icon('info')->list([
                Menu::make('Cron')->icon('calendar')->route('system.info.cron'),
                Menu::make('Cache')->icon('database')->route('system.cache'),
                Menu::make('Settings')->icon('settings')->route('system.settings.list'),
                Menu::make('Failed jobs')->icon('database')->route('system.failed.jobs'),
                Menu::make('Purge')->icon('trash')->route('system.purge'),
                Menu::make('Elasticsearch')->icon('database')->route('system.elasticsearch'),
                Menu::make('Database')->icon('database')->route('system.database'),
                Menu::make(__('Users'))->icon('bs.people')->route('platform.systems.users')->permission('platform.systems.users')->title(__('Access Controls')),
                Menu::make(__('Roles'))->icon('bs.shield')->route('platform.systems.roles')->permission('platform.systems.roles')->divider(),
            ])->divider(),

            Menu::make('API documentation')->icon('database')->target('_blank')->href('/api/documentation'),
            Menu::make('API documentation NEW')->icon('database')->target('_blank')->href('/api/docs'),
        ];
    }

    /**
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }
}
