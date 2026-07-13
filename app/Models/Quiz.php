<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'question',
        'options',
        'correct_answer',
        'points',
        'reason',
    ];

    protected $casts = [
        'options' => 'array', // otomatis cast JSON ke array
        'correct_answer' => 'integer', // ensure correct_answer is always int
        'points' => 'integer',
    ];

    /**
     * Relasi ke lesson
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Relasi ke jawaban user
     */
    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }

    /**
     * Cek apakah jawaban user benar
     */
    public function isAnswerCorrect($answer)
    {
        return $this->correct_answer === $answer;
    }

    /**
     * Ambil options dalam format array dengan label A, B, C, D
     */
    public function getFormattedOptionsAttribute()
    {
        $formatted = [];
        $labels = ['A', 'B', 'C', 'D'];
        
        foreach ($this->options as $index => $option) {
            $formatted[] = [
                'label' => $labels[$index],
                'text' => $option
            ];
        }
        
        return $formatted;
    }

    /**
     * Cek apakah user bisa retry quiz ini
     * User tidak bisa retry jika sudah pernah menjawab salah dan sudah melihat penjelasan
     */
    public function canRetry($userId)
    {
        $userAnswer = $this->userAnswers()
            ->where('user_id', $userId)
            ->first();

        // Jika belum pernah dijawab, bisa di-attempt
        if (!$userAnswer) {
            return true;
        }

        // Jika pernah dijawab benar, bisa lihat tapi tidak perlu retry
        if ($userAnswer->is_correct) {
            return false;
        }

        // Jika pernah dijawab salah dan sudah melihat reason, tidak bisa retry
        if (!$userAnswer->is_correct && $userAnswer->is_viewed_reason) {
            return false;
        }

        // Jika pernah dijawab salah tapi belum melihat reason, bisa retry
        return true;
    }

    /**
     * Dapatkan status quiz untuk user tertentu
     * Return: 'available', 'locked', 'completed'
     */
    public function getStatusForUser($userId)
    {
        $userAnswer = $this->userAnswers()
            ->where('user_id', $userId)
            ->first();

        if (!$userAnswer) {
            return 'available';
        }

        if ($userAnswer->is_correct) {
            return 'completed';
        }

        if ($userAnswer->is_viewed_reason) {
            return 'locked';
        }

        return 'available';
    }
}
