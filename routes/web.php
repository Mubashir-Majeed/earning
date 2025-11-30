<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/terms-of-service', 'legal.terms')->name('terms');
Route::view('/privacy-policy', 'legal.privacy')->name('privacy');

// Dashboard Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/deposit', [DashboardController::class, 'deposit'])->name('deposit');
    Route::get('/withdrawal', [DashboardController::class, 'withdrawal'])->name('withdrawal');
    Route::get('/earnings', [DashboardController::class, 'earnings'])->name('earnings');
    Route::get('/referrals', [DashboardController::class, 'referrals'])->name('referrals');
    Route::get('/level', [DashboardController::class, 'level'])->name('level');
    Route::post('/level/upgrade', [DashboardController::class, 'upgradeLevel'])->name('level.upgrade');
    Route::post('/tasks/channel-subscribe', [DashboardController::class, 'confirmChannelSubscription'])->name('tasks.channel-subscribe');
});

// Video Routes
Route::middleware(['auth'])->prefix('videos')->name('videos.')->group(function () {
    Route::get('/', [VideoController::class, 'index'])->name('index');
    Route::get('/{video}', [VideoController::class, 'show'])->name('show');
    Route::post('/{video}/start-watch', [VideoController::class, 'startWatch'])->name('start-watch');
    Route::post('/{video}/complete-watch', [VideoController::class, 'completeWatch'])->name('complete-watch');
    Route::get('/categories/all', [VideoController::class, 'categories'])->name('categories');
    Route::get('/category/{category}', [VideoController::class, 'byCategory'])->name('category');
});

// Payment Routes
Route::middleware(['auth'])->prefix('payment')->name('payment.')->group(function () {
    Route::post('/deposit', [PaymentController::class, 'deposit'])->name('deposit');
    Route::post('/stripe/webhook', [PaymentController::class, 'stripeWebhook'])->name('stripe.webhook');
});

// Withdrawal Routes
Route::middleware(['auth'])->prefix('withdrawal')->name('withdrawal.')->group(function () {
    Route::post('/request', [WithdrawalController::class, 'request'])->name('request');
    Route::post('/bind-wallet', [WithdrawalController::class, 'bindWallet'])->name('bind-wallet');
    Route::get('/history', [WithdrawalController::class, 'history'])->name('history');
});

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    Route::post('/users/{user}/toggle-active', [AdminController::class, 'toggleUserActive'])->name('users.toggle-active');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::get('/videos', [AdminController::class, 'videos'])->name('videos');
    Route::get('/videos/{video}/stats', [AdminController::class, 'videoStats'])->name('videos.stats');
    Route::get('/videos/create', [AdminController::class, 'createVideo'])->name('videos.create');
    Route::post('/videos', [AdminController::class, 'storeVideo'])->name('videos.store');
    Route::get('/videos/{video}/edit', [AdminController::class, 'editVideo'])->name('videos.edit');
    Route::put('/videos/{video}', [AdminController::class, 'updateVideo'])->name('videos.update');
    Route::get('/deposits', [AdminController::class, 'deposits'])->name('deposits');
    Route::get('/withdrawals', [AdminController::class, 'withdrawals'])->name('withdrawals');
    Route::post('/deposits/{deposit}/complete', [AdminController::class, 'completeDeposit'])->name('deposits.complete');
    Route::post('/deposits/{deposit}/fail', [AdminController::class, 'failDeposit'])->name('deposits.fail');
    Route::post('/withdrawals/{withdrawal}/approve', [AdminController::class, 'approveWithdrawal'])->name('withdrawals.approve');
    Route::post('/withdrawals/{withdrawal}/process', [AdminController::class, 'processWithdrawal'])->name('withdrawals.process');
    Route::post('/withdrawals/{withdrawal}/reject', [AdminController::class, 'rejectWithdrawal'])->name('withdrawals.reject');
    Route::get('/withdrawals/{withdrawal}/details', [AdminController::class, 'withdrawalDetails'])->name('withdrawals.details');
    Route::post('/withdrawals/{withdrawal}/note', [AdminController::class, 'addWithdrawalNote'])->name('withdrawals.note');
    
    // Referrals Management
    Route::get('/referrals', [AdminController::class, 'referrals'])->name('referrals');
    
    // Analytics Routes
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');
    
    // Settings Routes
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
});

// Notification Routes
Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('unread-count');
    Route::get('/recent', [NotificationController::class, 'recent'])->name('recent');
    Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('read');
    Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
    Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
});

// Profile Routes (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
