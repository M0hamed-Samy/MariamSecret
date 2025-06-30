<?php

use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home.index');


// Admin routes
Route::middleware(['auth', AuthAdmin::class])->group(function () {
    Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/brands', [App\Http\Controllers\AdminController::class, 'brands'])->name('admin.brands.index');
    Route::get('/admin/brands/create', [App\Http\Controllers\AdminController::class, 'createBrand'])->name('admin.brands.create');
    Route::post('/admin/brands/store', [App\Http\Controllers\AdminController::class, 'storeBrand'])->name('admin.brands.store');
    Route::get('/admin/brands/edit/{id}', [App\Http\Controllers\AdminController::class, 'editBrand'])->name('admin.brands.edit');
    Route::put('/admin/brands/update/{id}', [App\Http\Controllers\AdminController::class, 'updateBrand'])->name('admin.brands.update');
    Route::delete('/admin/brands/destroy/{id}', [App\Http\Controllers\AdminController::class, 'destroyBrand'])->name('admin.brands.destroy');

});


// User routes
Route::middleware(['auth'])->group(function () {
    Route::get('/account-dashboard', [App\Http\Controllers\UserController::class, 'index'])->name('user.index');
});
