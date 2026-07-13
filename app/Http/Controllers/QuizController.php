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
        $answer = (int)$request->input('answer'); // Cast to int for proper comparison
        
        // ANTI-CHEAT: Cek apakah user sudah pernah menjawab salah dan melihat penjelasan
        $existingAnswer = UserAnswer::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->first();
        
        if ($existingAnswer && !$existingAnswer->is_correct && $existingAnswer->is_viewed_reason) {
            return response()->json([
                'success' => false,
                'is_correct' => false,
                'message' => '🔒 Anda sudah melihat jawaban untuk soal ini. Tidak bisa mencoba lagi.',
                'is_locked' => true,
                'hearts' => $user->hearts->current_hearts
            ]);
        }
        
        $isCorrect = ((int)$quiz->correct_answer === $answer);
        
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
        
        // Simpan/update jawaban dengan tracking anti-cheat
        $attemptNumber = $existingAnswer ? $existingAnswer->attempt_number + 1 : 1;
        
        UserAnswer::updateOrCreate(
            ['user_id' => $user->id, 'quiz_id' => $quiz->id],
            [
                'answer' => $answer,
                'is_correct' => $isCorrect,
                'is_viewed_reason' => !$isCorrect, // Mark as viewed reason jika jawaban salah
                'attempt_number' => $attemptNumber
            ]
        );
        
        // Jika benar, update quest progress dan convert poin ke coin
        $coinsEarned = 0;
        if ($isCorrect) {
            $user->updateQuestProgress('answer_quiz', 1);
            
            // COIN CONVERSION: 10 poin = 1 coin
            $coinsEarned = intdiv($quiz->points, 10);
            if ($coinsEarned > 0) {
                $user->addCoins($coinsEarned);
            }
        }
        
        $options = $quiz->options;
        $correctAnswerText = $options[$quiz->correct_answer] ?? $quiz->correct_answer;
        $hearts = $user->hearts->current_hearts;
        $coins = $user->coins;
        
        return response()->json([
            'success' => true,
            'is_correct' => $isCorrect,
            'correct_answer' => $quiz->correct_answer,
            'correct_answer_text' => $correctAnswerText,
            'reason' => $quiz->reason,
            'points' => $isCorrect ? $quiz->points : 0,
            'coins_earned' => $coinsEarned,
            'total_coins' => $coins,
            'hearts' => $hearts,
            'message' => $isCorrect 
                ? '✅ Jawaban benar! +' . $quiz->points . ' poin (+' . $coinsEarned . ' coin) | ❤️ ' . $hearts 
                : '❌ Jawaban salah. ❤️ tersisa: ' . $hearts
        ]);
    }
}
