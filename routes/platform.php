<?php

declare(strict_types=1);

use App\Orchid\Screens\Catalog\Onliner\OnlinerCatalogGoodDetailsScreen;
use App\Orchid\Screens\Catalog\Onliner\OnlinerCatalogGoodsScreen;
use App\Orchid\Screens\Catalog\Onliner\OnlinerCatalogGroupsScreen;
use App\Orchid\Screens\Catalog\Onliner\OnlinerManufacturerScreen;
use App\Orchid\Screens\Catalog\Wildberries\WBCatalogCountriesScreen;
use App\Orchid\Screens\Catalog\Wildberries\WBCatalogGoodsDetailsScreen;
use App\Orchid\Screens\Catalog\Wildberries\WBCatalogGoodsScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\System\CacheScreen;
use App\Orchid\Screens\System\CronScreen;
use App\Orchid\Screens\System\DatabaseScreen;
use App\Orchid\Screens\System\DatabaseTableScreen;
use App\Orchid\Screens\System\ElasticsearchScreen;
use App\Orchid\Screens\System\FailedJobsScreen;
use App\Orchid\Screens\System\PurgeScreen;
use App\Orchid\Screens\System\SettingsScreen;
use App\Orchid\Screens\System\SupervisorScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users > User
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn(Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push($user->name, route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn(Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push($role->name, route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));

Route::screen('system/settings/list', SettingsScreen::class)->name('system.settings.list');
Route::screen('system/cache', CacheScreen::class)->name('system.cache');
Route::screen('system/cron', CronScreen::class)->name('system.info.cron');
Route::screen('system/purge', PurgeScreen::class)->name('system.purge');
Route::screen('system/failed-jobs', FailedJobsScreen::class)->name('system.failed.jobs');
Route::screen('system/database', DatabaseScreen::class)->name('system.database');
Route::screen('system/database/table/{table}', DatabaseTableScreen::class)->name('system.database.table');
Route::screen('system/elasticsearch', ElasticsearchScreen::class)->name('system.elasticsearch');
Route::screen('system/supervisor', SupervisorScreen::class)->name('system.supervisor');

// Catalog Onliner
Route::screen('catalog/onliner/goods/list', OnlinerCatalogGoodsScreen::class)->name('onliner.goods.list');
Route::screen('catalog/onliner/good/{id}/details', OnlinerCatalogGoodDetailsScreen::class)->name('onliner.goods.details');
Route::screen('catalog/onliner/manufacturers/list', OnlinerManufacturerScreen::class)->name('onliner.manufacturer.list');
Route::screen('catalog/onliner/types/list', OnlinerCatalogGroupsScreen::class)->name('onliner.type.list');

// Catalog Wildberries
Route::screen('catalog/wildberries/countries/list', WBCatalogCountriesScreen::class)->name('wb.countries.list');
Route::screen('catalog/wildberries/goods/list', WBCatalogGoodsScreen::class)->name('wb.goods.list');
Route::screen('catalog/wildberries/good/{id}/details', WBCatalogGoodsDetailsScreen::class)->name('wb.goods.details');
