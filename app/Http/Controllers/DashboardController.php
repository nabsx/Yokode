<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Lesson;
use App\Models\UserProgress;
use App\Models\UserQuest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // ========== AMBIL SEMUA KATEGORI DENGAN LESSONS ==========
        $categories = Category::where('is_active', true)
            ->orderBy('order')
            ->with(['lessons' => function ($query) {
                $query->orderBy('order_number');
            }])
            ->get();
        
        // ========== AMBIL COMPLETED LESSONS ==========
        $completedLessons = UserProgress::where('user_id', $user->id)
            ->where('completed', true)
            ->pluck('lesson_id')
            ->toArray();
        
        // ========== HITUNG PROGRESS PER KATEGORI ==========
        foreach ($categories as $category) {
            $total = $category->lessons->count();
            $completed = $category->lessons->filter(function ($lesson) use ($completedLessons) {
                return in_array($lesson->id, $completedLessons);
            })->count();
            
            $category->progress = $total > 0 ? round(($completed / $total) * 100) : 0;
            $category->completed_count = $completed;
            $category->total_count = $total;
        }
        
        // ========== STATISTIK GLOBAL ==========
        $totalLessons = Lesson::count();
        $completedCount = count($completedLessons);
        $progressPercentage = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100) : 0;
        
        // ========== GAMIFIKASI ==========
        $streak = $user->streak;
        $user->createDailyQuests();
        
        $dailyQuests = UserQuest::where('user_id', $user->id)
            ->where('date', now()->toDateString())
            ->with('dailyQuest')
            ->get();
        
        $completedQuests = $dailyQuests->where('completed', true)->count();
        $totalQuests = $dailyQuests->count();
        
        $recentAchievements = $user->achievements()
            ->orderBy('user_achievements.earned_at', 'desc')
            ->limit(3)
            ->get();
        
        $currentLevel = $user->level;
        $currentExp = $user->total_exp;
        $expToNextLevel = $user->exp_to_next_level;
        $levelProgress = $user->level_progress;
        
        return view('dashboard', compact(
            'categories',
            'completedLessons',
            'completedCount',        // <-- TAMBAHKAN INI
            'totalLessons',          // <-- TAMBAHKAN INI
            'progressPercentage',
            'streak',
            'dailyQuests',
            'completedQuests',
            'totalQuests',
            'recentAchievements',
            'currentLevel',
            'currentExp',
            'expToNextLevel',
            'levelProgress'
        ));
    }
}