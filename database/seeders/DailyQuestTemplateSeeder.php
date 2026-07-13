<?php

namespace Database\Seeders;

use App\Models\DailyQuestTemplate;
use Illuminate\Database\Seeder;

class DailyQuestTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'day_of_week' => 0, // Sunday
                'title' => 'Login Challenge',
                'description' => 'Login to the platform',
                'type' => 'login',
                'target' => 1,
                'reward_exp' => 50,
                'reward_coins' => 25,
            ],
            [
                'day_of_week' => 1, // Monday
                'title' => 'Lesson Completion',
                'description' => 'Complete 1 lesson',
                'type' => 'complete_lesson',
                'target' => 1,
                'reward_exp' => 100,
                'reward_coins' => 50,
            ],
            [
                'day_of_week' => 2, // Tuesday
                'title' => 'Quiz Master',
                'description' => 'Answer 5 quiz questions correctly',
                'type' => 'answer_quiz',
                'target' => 5,
                'reward_exp' => 150,
                'reward_coins' => 75,
            ],
            [
                'day_of_week' => 3, // Wednesday
                'title' => 'Experience Grinder',
                'description' => 'Gain 500 XP',
                'type' => 'gain_exp',
                'target' => 500,
                'reward_exp' => 200,
                'reward_coins' => 100,
            ],
            [
                'day_of_week' => 4, // Thursday
                'title' => 'Perfect Score',
                'description' => 'Answer 3 quizzes perfectly',
                'type' => 'perfect_quiz',
                'target' => 3,
                'reward_exp' => 180,
                'reward_coins' => 90,
            ],
            [
                'day_of_week' => 5, // Friday
                'title' => 'Lesson Marathon',
                'description' => 'Complete 2 lessons',
                'type' => 'complete_lesson',
                'target' => 2,
                'reward_exp' => 250,
                'reward_coins' => 125,
            ],
            [
                'day_of_week' => 6, // Saturday
                'title' => 'Weekend Warrior',
                'description' => 'Gain 1000 XP',
                'type' => 'gain_exp',
                'target' => 1000,
                'reward_exp' => 300,
                'reward_coins' => 150,
            ],
        ];

        foreach ($templates as $template) {
            DailyQuestTemplate::updateOrCreate(
                ['day_of_week' => $template['day_of_week']],
                $template
            );
        }

        $this->command->info('Daily quest templates seeded successfully!');
    }
}
