<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WhishListController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Auth::routes(['verify'=>true]);
// Public routes

//         Shop route
Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('shop/{product_slug}', [ShopController::class, 'showProductDetails'])->name('shop.show-details');


//         Cart route
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/store', [CartController::class, 'addToCart'])->name('cart.store');
Route::put('/cart/increase-qunatity/{rowId}', [CartController::class, 'increase_item_quantity'])->name('cart.increase.qty');
Route::put('/cart/reduce-qunatity/{rowId}', [CartController::class, 'reduce_item_quantity'])->name('cart.reduce.qty');
Route::delete('/cart/remove/{rowId}', [CartController::class, 'remove_item_from_cart'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'empty_cart'])->name('cart.empty');

//         Check-Out
Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/place-order', [CartController::class, 'place_order'])->name('cart.place_order');
Route::get('/order-confirmation', [CartController::class, 'order_confirmation'])->name('cart.confirmation');

//           Coupons
Route::post('/cart/apply-coupon', [CartController::class, 'apply_coupon_code'])->name('cart.coupon.apply');
Route::delete('/cart/remove-coupon', [CartController::class, 'remove_coupon_code'])->name('cart.coupon.remove');

//          Wishlist route
Route::post('/wishlist/add', [WhishListController::class, 'add'])->name('wishlist.add');
Route::get('/wishlist', [WhishListController::class, 'index'])->name('wishlist.index');
Route::delete('/wishlist/remove/{rowId}', [WhishListController::class, 'remove_item_from_wishlist'])->name('wishlist.remove');
Route::delete('/wishlist/clear', [WhishListController::class, 'empty_wishlist'])->name('wishlist.empty');
Route::post('/wishlist/move-to-cart/{rowId}', [WhishListController::class, 'move_to_cart'])->name('wishlist.move');



// Admin routes
Route::middleware(['auth', AuthAdmin::class])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    // Brands
    Route::get('/admin/brands', [AdminController::class, 'brands'])->name('admin.brands.index');
    Route::get('/admin/brands/create', [AdminController::class, 'createBrand'])->name('admin.brands.create');
    Route::post('/admin/brands/store', [AdminController::class, 'storeBrand'])->name('admin.brands.store');
    Route::get('/admin/brands/edit/{id}', [AdminController::class, 'editBrand'])->name('admin.brands.edit');
    Route::put('/admin/brands/update/{id}', [AdminController::class, 'updateBrand'])->name('admin.brands.update');
    Route::delete('/admin/brands/destroy/{id}', [AdminController::class, 'destroyBrand'])->name('admin.brands.destroy');

    // Categories]
    Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.category.index');
    Route::get('/admin/categories/create', [AdminController::class, 'createCategory'])->name('admin.category.create');
    Route::post('/admin/categories/store', [AdminController::class, 'storeCategory'])->name('admin.category.store');
    Route::get('/admin/categories/edit/{id}', [AdminController::class, 'editCategory'])->name('admin.category.edit');
    Route::put('/admin/categories/update/{id}', [AdminController::class, 'updateCategory'])->name('admin.category.update');
    Route::delete('/admin/categories/destroy/{id}', [AdminController::class, 'destroyCategory'])->name('admin.category.destroy');

    // Products
    Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products.index');
    Route::get('/admin/products/create', [AdminController::class, 'createProduct'])->name('admin.products.create');
    Route::post('/admin/products/store', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::get('/admin/products/edit/{id}', [AdminController::class, 'editProduct'])->name('admin.products.edit');
    Route::put('/admin/products/update/{id}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/admin/products/destroy/{id}', [AdminController::class, 'destroyProduct'])->name('admin.products.destroy');

    // Coupons
    Route::get('/admin/coupons', [AdminController::class, 'coupons'])->name('admin.coupons.index');
    Route::get('/admin/coupon/create', [AdminController::class, 'add_coupon'])->name('admin.coupons.create');
    Route::post('/admin/coupon/store', [AdminController::class, 'add_coupon_store'])->name('admin.coupon.store');
    Route::get('/admin/coupon/edit/{id}', [AdminController::class, 'edit_coupon'])->name('admin.coupon.edit');
    Route::put('/admin/coupon/update/{id}', [AdminController::class, 'update_coupon'])->name('admin.coupon.update');
    Route::delete('/admin/coupon/destroy/{id}', [AdminController::class, 'destroy_coupon'])->name('admin.coupon.destroy');

    // Orders
    Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.order.index');
    Route::get('/admin/order/{order_id}/details', [AdminController::class, 'order_details'])->name('admin.order.show');
    Route::put('/admin/order/update-status', [AdminController::class, 'update_order_status'])->name('admin.order.status.update');
    
    // Main-slide
    Route::get('/admin/slides',[AdminController::class,'slides'])->name('admin.slides.index');
    Route::get('/admin/slides/create',[AdminController::class, 'slide_add'])->name('admin.slides.create');
    Route::post('/admin/slides/store', [AdminController::class, 'slide_store'])->name('admin.slides.store');
    Route::get('/admin/slides/edit/{id}', [AdminController::class, 'slide_edit'])->name('admin.slides.edit');
    Route::put('/admin/slides/update',[AdminController::class, 'slide_update'])->name('admin.slides.update');
    Route::delete('/admin/slides/{id}', [AdminController::class, 'slide_destroy'])->name('admin.slides.destroy');

});


// User routes
Route::middleware(['auth','verified'])->group(function () {
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
    Route::get('/account-orders', [UserController::class, 'orders'])->name('user.orders');
    Route::get('/account-orders/{order_id}/details', [UserController::class, 'account_order_details'])->name('user.order.details');
    Route::put('/account-order/cancel-order', [UserController::class, 'account_cancel_order'])->name('user.account_cancel_order');
     Route::get('/account-details', [UserController::class, 'edit'])->name('account.edit');
    Route::post('/account-details/update', [UserController::class, 'update'])->name('account.update');
});
