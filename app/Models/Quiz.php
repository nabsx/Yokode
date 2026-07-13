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
}
