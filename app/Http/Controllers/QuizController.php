<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function submitAnswer(Request $request, int $id)
    {
        $quiz = Quiz::findOrFail($id);
        $user = Auth::user();
        $answer = $request->input('answer');
        
        $isCorrect = ($quiz->correct_answer === $answer);
        
        // HEARTS SYSTEM: Jika salah, kurangi heart
        if (!$isCorrect) {
            $canLoseHeart = $user->loseHeart();
            
            if (!$canLoseHeart) {
                return response()->json([
                    'success' => false,
                    'is_correct' => false,
                    'message' => '💔 Hearts habis! Tunggu recharge atau beli hearts di shop.',
                    'hearts' => 0,
                    'out_of_hearts' => true
                ]);
            }
        }
        
        // Simpan jawaban
        UserAnswer::updateOrCreate(
            ['user_id' => $user->id, 'quiz_id' => $quiz->id],
            [
                'answer' => $answer,
                'is_correct' => $isCorrect
            ]
        );
        
        // Jika benar, update quest progress
        if ($isCorrect) {
            $user->updateQuestProgress('answer_quiz', 1);
        }
        
        $options = $quiz->options;
        $correctAnswerText = $options[$quiz->correct_answer] ?? $quiz->correct_answer;
        $hearts = $user->hearts->current_hearts;
        
        return response()->json([
            'success' => true,
            'is_correct' => $isCorrect,
            'correct_answer' => $quiz->correct_answer,
            'correct_answer_text' => $correctAnswerText,
            'points' => $isCorrect ? $quiz->points : 0,
            'hearts' => $hearts,
            'message' => $isCorrect 
                ? '✅ Jawaban benar! +' . $quiz->points . ' poin | ❤️ ' . $hearts 
                : '❌ Jawaban salah. ❤️ tersisa: ' . $hearts
        ]);
    }
}