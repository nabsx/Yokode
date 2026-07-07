<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProgress extends Model
{
    use HasFactory;

    protected $table = 'user_progress';

    protected $fillable = [
        'user_id',
        'lesson_id',
        'completed',
        'score',
        'completed_at',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke lesson
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Tandai lesson sebagai selesai
     */
    public function markAsCompleted($score = null)
    {
        $this->completed = true;
        $this->completed_at = now();
        
        if ($score !== null) {
            $this->score = $score;
        }
        
        $this->save();
        
        // Tambah EXP ke user
        if ($this->lesson) {
            $this->user->addExp($this->lesson->exp_reward);
        }
        
        return $this;
    }
}