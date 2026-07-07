<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'daily_quest_id',
        'progress',
        'completed',
        'completed_at',
        'date',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'completed_at' => 'datetime',
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dailyQuest()
    {
        return $this->belongsTo(DailyQuest::class);
    }
}