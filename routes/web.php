<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ShopController::class, 'index'])->name('shop');

Route::get('/go/whatsapp', function () {
    $url = \App\Models\Setting::get('whatsapp_group');
    return $url ? redirect()->away($url) : redirect()->route('shop');
})->name('go.whatsapp');

Route::view('/contact', 'shop.contact')->name('contact');
Route::view('/social', 'shop.social')->name('social');

Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/admin/login', [AuthController::class, 'login'])->middleware(['guest', 'throttle:10,1']);

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::redirect('/', '/admin/products');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('password', [AuthController::class, 'password'])->name('password');

    Route::resource('products', ProductController::class)->except('show');
    Route::patch('products/{product}/status', [ProductController::class, 'status'])->name('products.status');
    Route::patch('products/{product}/today', [ProductController::class, 'today'])->name('products.today');

    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::get('settings', [SettingController::class, 'edit'])->name('settings');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
});
