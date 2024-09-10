<?php

use App\Http\Controllers\Api\v1\Catalog\CatalogController;
use Illuminate\Support\Facades\Route;

Route::get('/search', [CatalogController::class, 'searchGoods'])->name('search.goods');