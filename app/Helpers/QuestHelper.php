<?php

namespace App\Helpers;

use App\Models\DailyQuestTemplate;
use App\Models\DailyQuest;
use App\Models\UserQuest;
use App\Models\User;
use Carbon\Carbon;

class QuestHelper
{
    /**
     * Generate daily quests for a specific date from templates
     */
    public static function generateDailyQuests(?Carbon $date = null): ?DailyQuest
    {
        $date = $date ?? Carbon::now();
        $dayOfWeek = (int) $date->format('w');

        // Get template for this day of week
        $template = DailyQuestTemplate::where('day_of_week', $dayOfWeek)->first();

        if (!$template) {
            return null;
        }

        // Check if quest already exists for this date
        $existing = DailyQuest::where('date', $date->toDateString())->first();
        if ($existing) {
            return $existing;
        }

        // Create new daily quest from template
        $dailyQuest = DailyQuest::create([
            'title' => $template->title,
            'description' => $template->description,
            'type' => $template->type,
            'target' => $template->target,
            'reward_exp' => $template->reward_exp,
            'reward_coins' => $template->reward_coins,
            'date' => $date,
        ]);

        // Assign to all active users
        self::assignQuestToUsers($dailyQuest);

        return $dailyQuest;
    }

    /**
     * Assign a daily quest to all active users
     */
    public static function assignQuestToUsers(DailyQuest $quest): int
    {
        $users = User::where('role', 'user')->get();
        $assigned = 0;

        foreach ($users as $user) {
            // Check if user already has this quest
            $existing = UserQuest::where('user_id', $user->id)
                ->where('daily_quest_id', $quest->id)
                ->exists();

            if (!$existing) {
                UserQuest::create([
                    'user_id' => $user->id,
                    'daily_quest_id' => $quest->id,
                    'progress' => 0,
                    'completed' => false,
                    'date' => $quest->date,
                ]);
                $assigned++;
            }
        }

        return $assigned;
    }

    /**
     * Get today's quest
     */
    public static function getTodayQuest(): ?DailyQuest
    {
        return DailyQuest::where('date', Carbon::now()->toDateString())
            ->first();
    }

    /**
     * Get user's quest for today
     */
    public static function getUserTodayQuest(User $user): ?UserQuest
    {
        return UserQuest::where('user_id', $user->id)
            ->where('date', Carbon::now()->toDateString())
            ->first();
    }

    /**
     * Get template for specific day
     */
    public static function getTemplateForDay(int $dayOfWeek): ?DailyQuestTemplate
    {
        return DailyQuestTemplate::where('day_of_week', $dayOfWeek)->first();
    }

    /**
     * Get all templates
     */
    public static function getAllTemplates()
    {
        return DailyQuestTemplate::orderBy('day_of_week')->get();
    }

    /**
     * Get day name in Indonesian
     */
    public static function getDayNameId(int $dayOfWeek): string
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

        return $days[$dayOfWeek] ?? 'Unknown';
    }

    /**
     * Get day name in English
     */
    public static function getDayNameEn(int $dayOfWeek): string
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

        return $days[$dayOfWeek] ?? 'Unknown';
    }

    /**
     * Initialize default templates if not exists
     */
    public static function initializeDefaults(): array
    {
        $defaults = [
            [
                'day_of_week' => 0,
                'title' => 'Login Challenge',
                'description' => 'Login to the platform',
                'type' => 'login',
                'target' => 1,
                'reward_exp' => 50,
                'reward_coins' => 25,
            ],
            [
                'day_of_week' => 1,
                'title' => 'Lesson Completion',
                'description' => 'Complete 1 lesson',
                'type' => 'complete_lesson',
                'target' => 1,
                'reward_exp' => 100,
                'reward_coins' => 50,
            ],
            [
                'day_of_week' => 2,
                'title' => 'Quiz Master',
                'description' => 'Answer 5 quiz questions correctly',
                'type' => 'answer_quiz',
                'target' => 5,
                'reward_exp' => 150,
                'reward_coins' => 75,
            ],
            [
                'day_of_week' => 3,
                'title' => 'Experience Grinder',
                'description' => 'Gain 500 XP',
                'type' => 'gain_exp',
                'target' => 500,
                'reward_exp' => 200,
                'reward_coins' => 100,
            ],
            [
                'day_of_week' => 4,
                'title' => 'Perfect Score',
                'description' => 'Answer 3 quizzes perfectly',
                'type' => 'perfect_quiz',
                'target' => 3,
                'reward_exp' => 180,
                'reward_coins' => 90,
            ],
            [
                'day_of_week' => 5,
                'title' => 'Lesson Marathon',
                'description' => 'Complete 2 lessons',
                'type' => 'complete_lesson',
                'target' => 2,
                'reward_exp' => 250,
                'reward_coins' => 125,
            ],
            [
                'day_of_week' => 6,
                'title' => 'Weekend Warrior',
                'description' => 'Gain 1000 XP',
                'type' => 'gain_exp',
                'target' => 1000,
                'reward_exp' => 300,
                'reward_coins' => 150,
            ],
        ];

        $created = [];
        foreach ($defaults as $default) {
            $template = DailyQuestTemplate::updateOrCreate(
                ['day_of_week' => $default['day_of_week']],
                $default
            );
            $created[] = $template;
        }

        return $created;
    }
}
