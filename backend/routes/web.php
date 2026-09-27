<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaginationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SePayWebhookController;
use App\Http\Controllers\ShopAssistantController;
use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Route;

// =========================
// USER
// =========================

Route::get('/', [HomeController::class, 'index']);
Route::post('/assistant/chat', ShopAssistantController::class)->middleware('throttle:20,1')->name('assistant.chat');

Route::get('/products', [PaginationController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/voucher-preview', [CheckoutController::class, 'previewVoucher'])->name('checkout.voucher-preview');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/payment-status/{order}', [CheckoutController::class, 'paymentStatus'])->name('checkout.payment-status');
Route::post('/checkout/payment/{order}/cancel', [CheckoutController::class, 'cancelPendingPayment'])->name('checkout.payment-cancel');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::post('/sepay/webhook', SePayWebhookController::class)->name('sepay.webhook');

Route::get('/categories', [CategoryController::class, 'index']);

Route::get('/brands', [BrandController::class, 'index']);

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetCode'])->middleware('throttle:5,1')->name('password.email.send');
    Route::get('/verify-reset-code', [AuthController::class, 'showVerifyResetCode'])->name('password.code.form');
    Route::post('/verify-reset-code', [AuthController::class, 'verifyResetCode'])->name('password.code.verify');
    Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/vouchers', [VoucherController::class, 'index'])->name('vouchers.index');
    Route::post('/vouchers/redeem', [VoucherController::class, 'redeem'])->name('vouchers.redeem');
    Route::post('/vouchers/{voucher}/claim', [VoucherController::class, 'claim'])->name('vouchers.claim');
    Route::delete('/vouchers/{voucher}/remove', [VoucherController::class, 'remove'])->name('vouchers.remove');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{product}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{product}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::get('/change-password', [AuthController::class, 'showChangePassword'])->name('password.change');
    Route::put('/change-password', [AuthController::class, 'changePassword'])->name('password.update');
    Route::get('/verify-email', [AuthController::class, 'showVerifyEmail'])->name('email.verify');
    Route::post('/verify-email/send', [AuthController::class, 'sendVerificationCode'])->name('email.verification.send');
    Route::post('/verify-email', [AuthController::class, 'verifyEmail'])->name('email.verification.check');
});

// =========================
// ADMIN
// =========================

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/', [AdminController::class, 'index']);

    // Sản phẩm
    Route::get('/products', [ProductController::class, 'adminIndex']);

    Route::get('/products/create', [ProductController::class, 'create']);

    Route::post('/products', [ProductController::class, 'store']);

    Route::get('/products/{product}/edit', [ProductController::class, 'edit']);

    Route::put('/products/{product}', [ProductController::class, 'update']);

    Route::delete('/products/{product}', [ProductController::class, 'destroy']);

    // Danh mục
    Route::get('/categories', [CategoryController::class, 'adminIndex']);

    Route::get('/categories/create', [CategoryController::class, 'create']);

    Route::post('/categories', [CategoryController::class, 'store']);

    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit']);

    Route::put('/categories/{category}', [CategoryController::class, 'update']);

    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    // Thương hiệu
    Route::get('/brands', [BrandController::class, 'adminIndex']);

    Route::get('/brands/create', [BrandController::class, 'create']);

    Route::post('/brands', [BrandController::class, 'store']);

    Route::get('/brands/{brand}/edit', [BrandController::class, 'edit']);

    Route::put('/brands/{brand}', [BrandController::class, 'update']);

    Route::delete('/brands/{brand}', [BrandController::class, 'destroy']);

    Route::get('/vouchers', [VoucherController::class, 'adminIndex']);
    Route::get('/vouchers/create', [VoucherController::class, 'create']);
    Route::post('/vouchers', [VoucherController::class, 'store']);
    Route::get('/vouchers/{voucher}/edit', [VoucherController::class, 'edit']);
    Route::put('/vouchers/{voucher}', [VoucherController::class, 'update']);
    Route::delete('/vouchers/{voucher}', [VoucherController::class, 'destroy']);

    Route::get('/orders', [OrderController::class, 'adminIndex'])->name('admin.orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'adminShow'])->name('admin.orders.show');
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.status');

    Route::get('/users', [AdminController::class, 'users'])->name('admin.users.index');
    Route::patch('/users/{user}/lock', [AdminController::class, 'toggleUserLock'])->name('admin.users.lock');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
});
