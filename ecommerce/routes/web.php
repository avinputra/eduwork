<?php

use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContohController;
use App\Http\Controllers\ProductController;


Route::get('/', function () {
   // return view('welcome');
    return 'ini route utama';
});

/*
Route::get('/product', function () {
    return 'ini route products';
});
*/

Route::get('/cart', function () {
    return 'ini route cart';
});

Route::get('/checkout', function () {
     return 'ini route checkout';
});


Route::get('/contoh', [ContohController::class,'index']);

Route::resource('/product', ProductController::class);