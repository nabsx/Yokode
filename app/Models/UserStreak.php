<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStreak extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'current_streak',
        'longest_streak',
        'last_login_date',
    ];

    protected $casts = [
        'last_login_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}