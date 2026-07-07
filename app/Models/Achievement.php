<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'required_exp',
        'required_streak',
        'required_lessons',
        'required_quizzes',
        'reward_exp',
        'reward_coins',
    ];

    protected $casts = [
        'required_exp' => 'integer',
        'required_streak' => 'integer',
        'required_lessons' => 'integer',
        'required_quizzes' => 'integer',
        'reward_exp' => 'integer',
        'reward_coins' => 'integer',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_achievements')
            ->withPivot('earned_at')
            ->withTimestamps();
    }
}