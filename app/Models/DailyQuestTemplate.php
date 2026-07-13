<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyQuestTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'day_of_week',
        'title',
        'description',
        'type',
        'target',
        'reward_exp',
        'reward_coins',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'target' => 'integer',
        'reward_exp' => 'integer',
        'reward_coins' => 'integer',
    ];

    /**
     * Get day name
     */
    public function getDayName()
    {
        $days = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];

        return $days[$this->day_of_week] ?? 'Unknown';
    }

    /**
     * Get day name in English
     */
    public function getDayNameEn()
    {
        $days = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        return $days[$this->day_of_week] ?? 'Unknown';
    }

    /**
     * Create a DailyQuest from this template
     */
    public static function generateQuestForDay(int $dayOfWeek, \DateTime $date = null)
    {
        $template = self::where('day_of_week', $dayOfWeek)->first();
        
        if (!$template) {
            return null;
        }

        $date = $date ?? new \DateTime();

        return DailyQuest::create([
            'title' => $template->title,
            'description' => $template->description,
            'type' => $template->type,
            'target' => $template->target,
            'reward_exp' => $template->reward_exp,
            'reward_coins' => $template->reward_coins,
            'date' => $date,
        ]);
    }
}
