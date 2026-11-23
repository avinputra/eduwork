<?php

use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContohController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;


Route::get('/', [HomeController::class,'index']);
Route::get('/cart', [HomeController::class,'cart']);

/*
Route::get('/product', function () {
    return 'ini route products';
});


Route::get('/cart', function () {
    return 'ini route cart';
});

*/

Route::get('/checkout', function () {
     return 'ini route checkout';
});

/*
Route::get('/coba', function () {
    return view('coba');
});
*/

Route::get('/coba', [ContohController::class, 'coba']);

Route::get('/contoh', [ContohController::class,'index']);

Route::resource('/product', ProductController::class);