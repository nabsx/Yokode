<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini
use App\Http\Controllers\GamificationController;


// Halaman utama
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
});

// Auth routes (disediakan oleh laravel/ui)
Auth::routes();

// Protected routes (harus login)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Lesson
    Route::get('/lesson/{id}', [LessonController::class, 'show'])->name('lesson.show');
    Route::post('/lesson/{id}/complete', [LessonController::class, 'complete'])->name('lesson.complete');
    
    // Quiz
    Route::post('/quiz/{id}/submit', [QuizController::class, 'submitAnswer'])->name('quiz.submit');
    
    // Leaderboard
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
    
    // Di dalam middleware auth, tambahkan:
Route::get('/gamification/stats', [GamificationController::class, 'getUserStats'])->name('gamification.stats');
Route::get('/gamification/leaderboard', [GamificationController::class, 'getLeaderboard'])->name('gamification.leaderboard');
Route::get('/gamification/rank', [GamificationController::class, 'getUserRank'])->name('gamification.rank');

// Premium routes
Route::get('/premium', [PaymentController::class, 'index'])->name('premium.index');
Route::post('/premium/subscribe', [PaymentController::class, 'subscribe'])->name('premium.subscribe');
Route::get('/premium/success', [PaymentController::class, 'success'])->name('premium.success');
Route::get('/premium/cancel', [PaymentController::class, 'cancel'])->name('premium.cancel');

// Profile routes
Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');

// Shop routes
Route::get('/shop', [App\Http\Controllers\ShopController::class, 'index'])->name('shop.index');
Route::post('/shop/{id}/buy', [App\Http\Controllers\ShopController::class, 'buy'])->name('shop.buy');
Route::get('/shop/inventory', [App\Http\Controllers\ShopController::class, 'inventory'])->name('shop.inventory');
Route::post('/shop/inventory/{id}/use', [App\Http\Controllers\ShopController::class, 'useItem'])->name('shop.use');
});