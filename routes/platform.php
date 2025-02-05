<?php

declare(strict_types=1);

use App\Orchid\Screens\Catalog\CatalogGoodDetailsScreen;
use App\Orchid\Screens\Catalog\CatalogGoodsScreen;
use App\Orchid\Screens\Catalog\CatalogTypesScreen;
use App\Orchid\Screens\Catalog\ManufacturerScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\System\CacheScreen;
use App\Orchid\Screens\System\CronScreen;
use App\Orchid\Screens\System\PurgeScreen;
use App\Orchid\Screens\System\SettingsScreen;
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

// Catalog
Route::screen('catalog/goods/list', CatalogGoodsScreen::class)->name('catalog.goods.list');
Route::screen('catalog/good/{id}/details', CatalogGoodDetailsScreen::class)->name('catalog.good.details');
Route::screen('catalog/manufacturers/list', ManufacturerScreen::class)->name('catalog.manufacturer.list');
Route::screen('catalog/types/list', CatalogTypesScreen::class)->name('catalog.type.list');
