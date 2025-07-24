<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\VipResellerController;
use App\Http\Controllers\DashboardTransactionController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\TransactionController;

Route::get('/admin/transaksi/cetak', [TransactionController::class, 'cetakLaporan'])->name('admin-transaction-print');

Route::get('/admin/notifikasi/check', [AdminNotificationController::class, 'check'])->name('admin.notifikasi.check');

Route::get('/dashboard/transaction/{id}', [DashboardTransactionController::class, 'show'])
    ->name('dashboard-transaction-details');

Route::get('/cek-game', [VipResellerController::class, 'getGameServices']);
Route::get('/cek-pulsa', [VipResellerController::class, 'getPulsaServices']);

Route::post('/midtrans/callback', [MidtransController::class, 'callback']);

// Halaman Utama
Route::get('/', [HomeController::class, 'index'])->name('home');

// Kategori & Detail Produk
Route::get('/categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories');
Route::get('/categories/{id}', [App\Http\Controllers\CategoryController::class, 'detail'])->name('categories-detail');

// Beli Produk Langsung
Route::get('/buy/{slug}', [CheckoutController::class, 'buyForm'])->name('buy.form');
Route::post('/buy/{slug}', [CheckoutController::class, 'process'])->middleware('auth')->name('buy.process');

// Payment hasil
Route::get('/payment/success', [CheckoutController::class, 'success'])->name('payment.success');
Route::get('/payment/failed', [CheckoutController::class, 'failed'])->name('payment.failed');

// Sukses & Register
Route::get('/success', [CheckoutController::class, 'success'])->name('success');
Route::get('/register/success', [App\Http\Controllers\Auth\RegisterController::class, 'success'])->name('register-success');

// FAQ
Route::get('/faq', fn() => view('pages/faq'))->name('faq');

// Dashboard Kategori (untuk user)
Route::get('/dashboard/category', [App\Http\Controllers\DashboardController::class, 'category'])->name('dashboard-category');

// Grup Route yang memerlukan Login
// Grup Route yang memerlukan Login
Route::middleware(['auth'])->group(function () {
    // Dashboard User
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Transaksi
    Route::prefix('dashboard/transactions')->group(function () {
        Route::get('/', [DashboardTransactionController::class, 'index'])->name('dashboard-transaction');
        Route::get('/{id}', [DashboardTransactionController::class, 'detailsBuy'])->name('dashboard-transaction-details');
        Route::put('/{id}', [DashboardTransactionController::class, 'update'])->name('dashboard-transaction-update');
    });

    // Pengaturan Akun
    Route::get('/dashboard/account', [App\Http\Controllers\DashboardSettingController::class, 'account'])->name('dashboard-settings-account');
    Route::put('/dashboard/account/{redirect}', [App\Http\Controllers\DashboardSettingController::class, 'update'])->name('dashboard-settings-redirect');

    // Review
    Route::post('/review/{id}', [ReviewController::class, 'store'])->name('review-add');

});

// Admin Panel
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin-dashboard');

    Route::get('/transaction-notification', [App\Http\Controllers\Admin\TransactionController::class, 'notification'])->name('admin.transaction.notification');

    Route::get('/transaction', [App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('admin-transaction');
    Route::get('/transaction/{id}', [App\Http\Controllers\Admin\TransactionController::class, 'show'])->name('admin-transaction-detail');

    Route::resource('/category', App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('/user', App\Http\Controllers\Admin\UserController::class);
    Route::resource('/product', App\Http\Controllers\Admin\ProductController::class);
    Route::resource('/product-gallery', App\Http\Controllers\Admin\ProductGalleryController::class);
});

// Autentikasi Laravel
Auth::routes();

// Halaman tipe kategori utama (Game / Pulsa)
Route::get('/category/type', [App\Http\Controllers\CategoryController::class, 'type'])->name('category.type');

// Menampilkan subkategori berdasarkan tipe (misal: Game -> Free Fire, Mobile Legends)
Route::get('/category/type/{slug}', [App\Http\Controllers\CategoryController::class, 'byType'])->name('category.byType');

// Store Profile (Diletakkan paling bawah agar tidak konflik dengan route lain)
Route::get('/store/{slugSeller}', [HomeController::class, 'storeProfile'])->name('store-profile');
