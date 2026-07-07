<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'exp_reward',
        'is_premium',
        'order_number',
    ];

    protected $casts = [
        'is_premium' => 'boolean',
    ];

    /**
     * Relasi ke quiz (satu lesson punya banyak quiz)
     */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    /**
     * Relasi ke user progress
     */
    public function userProgresses()
    {
        return $this->hasMany(UserProgress::class);
    }

    /**
     * Cek apakah lesson sudah diselesaikan oleh user tertentu
     */
    public function isCompletedByUser($userId)
    {
        return $this->userProgresses()
            ->where('user_id', $userId)
            ->where('completed', true)
            ->exists();
    }
}