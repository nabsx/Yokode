<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyQuest extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'target',
        'reward_exp',
        'reward_coins',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function userQuests()
    {
        return $this->hasMany(UserQuest::class);
    }
}