<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\User;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function show(int $id)  // PERBAIKAN: tambah type hint int
    {
        $lesson = Lesson::findOrFail($id);
        $user = Auth::user();
        
        // Cek akses premium
        if ($lesson->is_premium && !$user->is_premium_active) {
            return redirect()->route('dashboard')
                ->with('error', 'Modul ini membutuhkan akses premium. Silakan upgrade terlebih dahulu.');
        }
        
        // Cek apakah lesson sudah selesai
        $isCompleted = UserProgress::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->where('completed', true)
            ->exists();
        
        // Ambil semua quiz untuk lesson ini
        $quizzes = $lesson->quizzes;
        
        // ANTI-CHEAT: Tambahkan status untuk setiap quiz
        $quizzesWithStatus = $quizzes->map(function($quiz) use ($user) {
            return [
                'id' => $quiz->id,
                'question' => $quiz->question,
                'options' => $quiz->options,
                'correct_answer' => $quiz->correct_answer,
                'points' => $quiz->points,
                'reason' => $quiz->reason,
                'can_retry' => $quiz->canRetry($user->id),
                'status' => $quiz->getStatusForUser($user->id),
                'user_answer' => $quiz->userAnswers()->where('user_id', $user->id)->first(),
            ];
        });
        
        return view('lessons.show', compact('lesson', 'quizzes', 'quizzesWithStatus', 'isCompleted'));
    }
    
    public function complete(int $id)
{
    $lesson = Lesson::findOrFail($id);
    $user = Auth::user();
    
    $progress = UserProgress::where('user_id', $user->id)
        ->where('lesson_id', $lesson->id)
        ->first();
    
    if ($progress && $progress->completed) {
        return response()->json([
            'success' => false,
            'message' => 'Lesson already completed'
        ], 400);
    }
    
    UserProgress::updateOrCreate(
        ['user_id' => $user->id, 'lesson_id' => $lesson->id],
        [
            'completed' => true,
            'completed_at' => now(),
            'score' => 100
        ]
    );
    
    // Update quest progress
    $user->updateQuestProgress('complete_lesson', 1);
    
    // Tambah EXP (multiplier sudah di handle di addExp)
    $user->addExp($lesson->exp_reward);
    
    $freshUser = User::find($user->id);
    
    return response()->json([
        'success' => true,
        'message' => 'Lesson completed! +' . $lesson->exp_reward . ' EXP',
        'exp_gained' => $lesson->exp_reward,
        'total_exp' => $freshUser->total_exp,
        'new_level' => $freshUser->level,
        'exp_to_next_level' => $freshUser->exp_to_next_level,
        'level_progress' => $freshUser->level_progress,
        'streak' => $freshUser->streak->current_streak,
        'league' => $freshUser->league
    ]);
}
}
