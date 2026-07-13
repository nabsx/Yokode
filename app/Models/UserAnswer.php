<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'answer',
        'is_correct',
        'is_viewed_reason',
        'attempt_number',
        'locked_until',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'is_viewed_reason' => 'boolean',
        'attempt_number' => 'integer',
        'locked_until' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
