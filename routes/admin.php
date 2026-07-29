<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\PromoCodeController;
use App\Http\Controllers\Admin\CoinController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\CleaningController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\SettingsController;

Route::prefix('admin')->name('admin.')->middleware(['web', 'admin'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/analytics/revenue', [AnalyticsController::class, 'revenue'])->name('analytics.revenue');
    Route::get('/analytics/gmv', [AnalyticsController::class, 'gmv'])->name('analytics.gmv');

    // Products
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/{product}', [ProductController::class, 'show'])->name('show');
        Route::patch('/{product}/approve', [ProductController::class, 'approve'])->name('approve');
        Route::patch('/{product}/reject', [ProductController::class, 'reject'])->name('reject');
        Route::patch('/{product}/feature', [ProductController::class, 'feature'])->name('feature');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
    });
    // Alias for simple route name used in nav
    Route::get('/products', [ProductController::class, 'index'])->name('products');

    // Categories
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::patch('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');

    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::patch('/{order}/resolve', [OrderController::class, 'resolve'])->name('resolve');
        Route::patch('/{order}/refund', [OrderController::class, 'refund'])->name('refund');
    });
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');

    // Promo Codes
    Route::prefix('promocodes')->name('promocodes.')->group(function () {
        Route::get('/', [PromoCodeController::class, 'index'])->name('index');
        Route::post('/', [PromoCodeController::class, 'store'])->name('store');
        Route::patch('/{code}', [PromoCodeController::class, 'update'])->name('update');
        Route::delete('/{code}', [PromoCodeController::class, 'destroy'])->name('destroy');
    });
    Route::get('/promocodes', [PromoCodeController::class, 'index'])->name('promocodes');

    // Reward Coins
    Route::prefix('coins')->name('coins.')->group(function () {
        Route::get('/', [CoinController::class, 'index'])->name('index');
        Route::patch('/settings', [CoinController::class, 'updateSettings'])->name('settings');
        Route::post('/award', [CoinController::class, 'award'])->name('award');
        Route::post('/deduct', [CoinController::class, 'deduct'])->name('deduct');
    });
    Route::get('/coins', [CoinController::class, 'index'])->name('coins');

    // Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::patch('/{user}/verify', [UserController::class, 'verify'])->name('verify');
        Route::patch('/{user}/suspend', [UserController::class, 'suspend'])->name('suspend');
        Route::patch('/{user}/unsuspend', [UserController::class, 'unsuspend'])->name('unsuspend');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });
    Route::get('/users', [UserController::class, 'index'])->name('users');

    // Reviews
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', [ReviewController::class, 'index'])->name('index');
        Route::patch('/{review}/approve', [ReviewController::class, 'approve'])->name('approve');
        Route::delete('/{review}', [ReviewController::class, 'destroy'])->name('destroy');
    });
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews');

    // Cleaning Queue
    Route::prefix('cleaning')->name('cleaning.')->group(function () {
        Route::get('/', [CleaningController::class, 'index'])->name('index');
        Route::patch('/{item}/complete', [CleaningController::class, 'complete'])->name('complete');
        Route::patch('/{item}/assign', [CleaningController::class, 'assign'])->name('assign');
    });
    Route::get('/cleaning', [CleaningController::class, 'index'])->name('cleaning');

    // Chat / Messages
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('/{conversation}', [ChatController::class, 'show'])->name('show');
        Route::delete('/{message}', [ChatController::class, 'destroy'])->name('destroy');
    });
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');

    // Contact Messages
    Route::prefix('contact')->name('contact.')->group(function () {
        Route::get('/', [ContactController::class, 'index'])->name('index');
        Route::get('/{message}', [ContactController::class, 'show'])->name('show');
        Route::patch('/{message}/resolve', [ContactController::class, 'resolve'])->name('resolve');
        Route::delete('/{message}', [ContactController::class, 'destroy'])->name('destroy');
    });
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');

    // Reports / Flags
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::patch('/{report}/dismiss', [ReportController::class, 'dismiss'])->name('dismiss');
        Route::patch('/{report}/action', [ReportController::class, 'action'])->name('action');
    });
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');

    // Push Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/send', [NotificationController::class, 'send'])->name('send');
        Route::get('/history', [NotificationController::class, 'history'])->name('history');
    });
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');

    // Blog
    Route::prefix('blog')->name('blog.')->group(function () {
        Route::get('/', [BlogController::class, 'index'])->name('index');
        Route::get('/create', [BlogController::class, 'create'])->name('create');
        Route::post('/', [BlogController::class, 'store'])->name('store');
        Route::get('/{post}/edit', [BlogController::class, 'edit'])->name('edit');
        Route::patch('/{post}', [BlogController::class, 'update'])->name('update');
        Route::delete('/{post}', [BlogController::class, 'destroy'])->name('destroy');
    });
    Route::get('/blog', [BlogController::class, 'index'])->name('blog');

    // FAQs
    Route::prefix('faqs')->name('faqs.')->group(function () {
        Route::get('/', [FaqController::class, 'index'])->name('index');
        Route::post('/', [FaqController::class, 'store'])->name('store');
        Route::patch('/{faq}', [FaqController::class, 'update'])->name('update');
        Route::delete('/{faq}', [FaqController::class, 'destroy'])->name('destroy');
    });
    Route::get('/faqs', [FaqController::class, 'index'])->name('faqs');

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::patch('/fees', [SettingsController::class, 'updateFees'])->name('fees');
        Route::patch('/coins', [SettingsController::class, 'updateCoins'])->name('coins');
        Route::patch('/content-policy', [SettingsController::class, 'updateContentPolicy'])->name('content-policy');
        Route::patch('/legal', [SettingsController::class, 'updateLegal'])->name('legal');
    });
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
});
