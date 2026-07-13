<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Lesson;
use App\Models\UserProgress;
use App\Models\UserQuest;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // ========== AMBIL SEMUA KATEGORI DENGAN LESSONS ==========
        // Get all categories (active or not) to ensure newly created ones show up immediately
        $categories = Category::orderBy('order')
            ->with(['lessons' => function ($query) {
                $query->orderBy('order_number')->get();
            }])
            ->get();
        
        // ========== AMBIL COMPLETED LESSONS ==========
        // Use fresh query to ensure latest data without cache
        $completedLessons = UserProgress::where('user_id', $user->id)
            ->where('completed', true)
            ->get()
            ->pluck('lesson_id')
            ->toArray();
        
        // ========== HITUNG PROGRESS PER KATEGORI + ANTI-CHEAT STATUS ==========
        foreach ($categories as $category) {
            $total = $category->lessons->count();
            $completed = $category->lessons->filter(function ($lesson) use ($completedLessons) {
                return in_array($lesson->id, $completedLessons);
            })->count();
            
            $category->progress = $total > 0 ? round(($completed / $total) * 100) : 0;
            $category->completed_count = $completed;
            $category->total_count = $total;
            
            // ANTI-CHEAT: Tambahkan status untuk setiap lesson
            $category->lessons = $category->lessons->map(function ($lesson) use ($user, $completedLessons) {
                $lesson->is_completed = in_array($lesson->id, $completedLessons);
                $lesson->lesson_status = $this->getLessonStatus($lesson->id, $user->id);
                return $lesson;
            });
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

    /**
     * ANTI-CHEAT: Tentukan status lesson untuk user
     * Return: 'available', 'has_locked_quiz', 'completed'
     * 
     * Note: Lesson tetap bisa dibuka meski ada quiz terkunci (untuk selesaikan).
     * Status 'has_locked_quiz' hanya informatif, tidak mencegah akses.
     */
    private function getLessonStatus(int $lessonId, int $userId): string
    {
        // Cek apakah lesson sudah completed
        $isCompleted = UserProgress::where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->where('completed', true)
            ->exists();
        
        if ($isCompleted) {
            return 'completed';
        }
        
        // Cek apakah ada quiz yang sudah dijawab salah dan viewed reason
        $hasLockedQuiz = UserAnswer::join('quizzes', 'user_answers.quiz_id', '=', 'quizzes.id')
            ->where('user_answers.user_id', $userId)
            ->where('quizzes.lesson_id', $lessonId)
            ->where('user_answers.is_correct', false)
            ->where('user_answers.is_viewed_reason', true)
            ->exists();
        
        if ($hasLockedQuiz) {
            return 'has_locked_quiz';
        }
        
        return 'available';
    }
}
