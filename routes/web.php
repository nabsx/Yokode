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

// Admin routes (harus login dan admin role)
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');

    // User Management
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'usersIndex'])->name('users.index');
    Route::get('/users/{user}', [App\Http\Controllers\AdminController::class, 'usersShow'])->name('users.show');
    Route::get('/users/{user}/edit', [App\Http\Controllers\AdminController::class, 'usersEdit'])->name('users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\AdminController::class, 'usersUpdate'])->name('users.update');
    Route::delete('/users/{user}', [App\Http\Controllers\AdminController::class, 'usersDestroy'])->name('users.destroy');

    // Lesson/Module Management
    Route::get('/lessons', [App\Http\Controllers\AdminController::class, 'lessonsIndex'])->name('lessons.index');
    Route::get('/lessons/create', [App\Http\Controllers\AdminController::class, 'lessonsCreate'])->name('lessons.create');
    Route::post('/lessons', [App\Http\Controllers\AdminController::class, 'lessonsStore'])->name('lessons.store');
    Route::get('/lessons/{lesson}/edit', [App\Http\Controllers\AdminController::class, 'lessonsEdit'])->name('lessons.edit');
    Route::put('/lessons/{lesson}', [App\Http\Controllers\AdminController::class, 'lessonsUpdate'])->name('lessons.update');
    Route::delete('/lessons/{lesson}', [App\Http\Controllers\AdminController::class, 'lessonsDestroy'])->name('lessons.destroy');

    // Category Management
    Route::get('/categories', [App\Http\Controllers\AdminController::class, 'categoriesIndex'])->name('categories.index');
    Route::get('/categories/create', [App\Http\Controllers\AdminController::class, 'categoriesCreate'])->name('categories.create');
    Route::post('/categories', [App\Http\Controllers\AdminController::class, 'categoriesStore'])->name('categories.store');
    Route::get('/categories/{category}/edit', [App\Http\Controllers\AdminController::class, 'categoriesEdit'])->name('categories.edit');
    Route::put('/categories/{category}', [App\Http\Controllers\AdminController::class, 'categoriesUpdate'])->name('categories.update');
    Route::delete('/categories/{category}', [App\Http\Controllers\AdminController::class, 'categoriesDestroy'])->name('categories.destroy');

    // Quiz Management
    Route::get('/quizzes', [App\Http\Controllers\AdminController::class, 'quizzesIndex'])->name('quizzes.index');

    // Gamification Management
    Route::get('/achievements', [App\Http\Controllers\AdminController::class, 'achievementsIndex'])->name('achievements.index');
    Route::get('/shop-items', [App\Http\Controllers\AdminController::class, 'shopItemsIndex'])->name('shop.index');
    Route::get('/daily-quests', [App\Http\Controllers\AdminController::class, 'dailyQuestsIndex'])->name('quests.index');

    // Analytics & Reports
    Route::get('/analytics', [App\Http\Controllers\AdminController::class, 'analytics'])->name('analytics');
});
