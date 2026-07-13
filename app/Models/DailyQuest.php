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
        'day_of_week',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function userQuests()
    {
        return $this->hasMany(UserQuest::class);
    }

    // Get quest template for a specific day of week (0=Monday, 6=Sunday)
    public static function getByDayOfWeek($dayOfWeek)
    {
        return self::where('day_of_week', $dayOfWeek)->first();
    }

    // Get all weekly quest templates organized by day
    public static function getWeeklyTemplates()
    {
        $days = [
            0 => 'Monday',
            1 => 'Tuesday',
            2 => 'Wednesday',
            3 => 'Thursday',
            4 => 'Friday',
            5 => 'Saturday',
            6 => 'Sunday',
        ];

        $templates = [];
        foreach ($days as $dayNum => $dayName) {
            $templates[$dayNum] = [
                'day_name' => $dayName,
                'quest' => self::getByDayOfWeek($dayNum),
            ];
        }
        return $templates;
    }

    // Get day name for display
    public function getDayName()
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        return $this->day_of_week !== null ? $days[$this->day_of_week] : null;
    }
}
