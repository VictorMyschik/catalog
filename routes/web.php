<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Clear cache
Route::get('/clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');

    return back();
})->name('clear');
Auth::routes();

Route::get('/', function () {
    return redirect(LoginController::HOME);
});

Route::get('/test', [TestController::class, 'index']);

Auth::routes();

Route::get('/home', function () {
    return redirect(LoginController::HOME);
});