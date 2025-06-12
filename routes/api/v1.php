<?php

use App\Http\Controllers\Api\DownloadFileController;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Catalog\CatalogController;
use App\Http\Controllers\Api\V1\User\UsersController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/reset-password/code', [AuthController::class, 'resetPasswordCode']);
Route::post('/reset-password/change', [AuthController::class, 'resetPasswordChange']);

Route::middleware('auth:sanctum')->group(static function () {
    Route::any('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::any('/logout-all', [AuthController::class, 'logoutAllSessions'])->name('logoutAllSessions');

    Route::post('/user/password', [AuthController::class, 'changePassword']);
    Route::post('/user/verify', [AuthController::class, 'verifyRegistration']);
    Route::post('/user/verify/resend', [AuthController::class, 'verifyResend']);
    Route::post('/user/profile', [UsersController::class, 'updateProfile']);
    Route::get('/user', [UsersController::class, 'profile']);

    Route::middleware('api-verified')->group(static function () {
        Route::post('/catalog/search', [CatalogController::class, 'searchGoods'])->name('search.goods');
    });
});

Route::get('/download', [DownloadFileController::class, 'download'])->name('download');
