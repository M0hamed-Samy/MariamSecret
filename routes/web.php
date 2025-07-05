<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WhishListController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Auth::routes();
// Public routes

//         Shop route
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home.index');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('shop/{product_slug}', [ShopController::class, 'showProductDetails'])->name('shop.show-details');


//         Cart route
Route::get('cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/store', [CartController::class, 'addToCart'])->name('cart.store');
Route::put('/cart/increase-qunatity/{rowId}', [CartController::class, 'increase_item_quantity'])->name('cart.increase.qty');
Route::put('/cart/reduce-qunatity/{rowId}', [CartController::class, 'reduce_item_quantity'])->name('cart.reduce.qty');
Route::delete('/cart/remove/{rowId}', [CartController::class, 'remove_item_from_cart'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'empty_cart'])->name('cart.empty');

//           Coupons
Route::post('/cart/apply-coupon',[CartController::class,'apply_coupon_code'])->name('cart.coupon.apply');
Route::delete('/cart/remove-coupon',[CartController::class,'remove_coupon_code'])->name('cart.coupon.remove');

//          Wishlist route
Route::post('/wishlist/add', [WhishListController::class, 'add'])->name('wishlist.add');
Route::get('wishlist', [WhishListController::class, 'index'])->name('wishlist.index');
Route::delete('/wishlist/remove/{rowId}', [WhishListController::class, 'remove_item_from_wishlist'])->name('wishlist.remove');
Route::delete('/wishlist/clear', [WhishListController::class, 'empty_wishlist'])->name('wishlist.empty');
Route::post('/wishlist/move-to-cart/{rowId}', [WhishListController::class, 'move_to_cart'])->name('wishlist.move');



// Admin routes
Route::middleware(['auth', AuthAdmin::class])->group(function () {
    Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');
    // Brands
    Route::get('/admin/brands', [App\Http\Controllers\AdminController::class, 'brands'])->name('admin.brands.index');
    Route::get('/admin/brands/create', [App\Http\Controllers\AdminController::class, 'createBrand'])->name('admin.brands.create');
    Route::post('/admin/brands/store', [App\Http\Controllers\AdminController::class, 'storeBrand'])->name('admin.brands.store');
    Route::get('/admin/brands/edit/{id}', [App\Http\Controllers\AdminController::class, 'editBrand'])->name('admin.brands.edit');
    Route::put('/admin/brands/update/{id}', [App\Http\Controllers\AdminController::class, 'updateBrand'])->name('admin.brands.update');
    Route::delete('/admin/brands/destroy/{id}', [App\Http\Controllers\AdminController::class, 'destroyBrand'])->name('admin.brands.destroy');

    // Categories]
    Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.category.index');
    Route::get('/admin/categories/create', [App\Http\Controllers\AdminController::class, 'createCategory'])->name('admin.category.create');
    Route::post('/admin/categories/store', [App\Http\Controllers\AdminController::class, 'storeCategory'])->name('admin.category.store');
    Route::get('/admin/categories/edit/{id}', [App\Http\Controllers\AdminController::class, 'editCategory'])->name('admin.category.edit');
    Route::put('/admin/categories/update/{id}', [App\Http\Controllers\AdminController::class, 'updateCategory'])->name('admin.category.update');
    Route::delete('/admin/categories/destroy/{id}', [App\Http\Controllers\AdminController::class, 'destroyCategory'])->name('admin.category.destroy');

    // Products
    Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products.index');
    Route::get('/admin/products/create', [App\Http\Controllers\AdminController::class, 'createProduct'])->name('admin.products.create');
    Route::post('/admin/products/store', [App\Http\Controllers\AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::get('/admin/products/edit/{id}', [App\Http\Controllers\AdminController::class, 'editProduct'])->name('admin.products.edit');
    Route::put('/admin/products/update/{id}', [App\Http\Controllers\AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/admin/products/destroy/{id}', [App\Http\Controllers\AdminController::class, 'destroyProduct'])->name('admin.products.destroy');

    // Coupons
    Route::get('admin/coupons', [AdminController::class, 'coupons'])->name('admin.coupons.index');
    Route::get('/admin/coupon/create', [AdminController::class, 'add_coupon'])->name('admin.coupons.create');
    Route::post('/admin/coupon/store', [AdminController::class, 'add_coupon_store'])->name('admin.coupon.store');
    Route::get('/admin/coupon/edit/{id}', [App\Http\Controllers\AdminController::class, 'edit_coupon'])->name('admin.coupon.edit');
    Route::put('/admin/coupon/update/{id}', [AdminController::class, 'update_coupon'])->name('admin.coupon.update');
    Route::delete('/admin/coupon/destroy/{id}', [App\Http\Controllers\AdminController::class, 'destroy_coupon'])->name('admin.coupon.destroy');



});


// User routes
Route::middleware(['auth'])->group(function () {
    Route::get('/account-dashboard', [App\Http\Controllers\UserController::class, 'index'])->name('user.index');
});
