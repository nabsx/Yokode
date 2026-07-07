<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function getProgressAttribute()
    {
        $total = $this->lessons->count();
        if ($total == 0) return 0;
        
        $completed = $this->lessons->filter(function ($lesson) {
            return auth()->user() && auth()->user()->hasCompletedLesson($lesson);
        })->count();
        
        return round(($completed / $total) * 100);
    }
}