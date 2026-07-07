<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\ShopItem;
use Illuminate\Database\Seeder;

class GamificationSeeder extends Seeder
{
    public function run(): void
    {
        // Achievements
        $achievements = [
            ['name' => 'First Step', 'slug' => 'first_step', 'description' => 'Complete your first lesson', 'icon' => '🎯', 'required_lessons' => 1, 'reward_exp' => 50],
            ['name' => 'On Fire!', 'slug' => 'on_fire', 'description' => '7 day streak', 'icon' => '🔥', 'required_streak' => 7, 'reward_exp' => 200],
            ['name' => 'Legendary', 'slug' => 'legendary', 'description' => 'Reach level 10', 'icon' => '👑', 'required_exp' => 1000, 'reward_exp' => 500],
            ['name' => 'Quiz Master', 'slug' => 'quiz_master', 'description' => 'Answer 100 quizzes correctly', 'icon' => '📝', 'required_quizzes' => 100, 'reward_exp' => 300],
            ['name' => 'Perfect Week', 'slug' => 'perfect_week', 'description' => '7 days streak', 'icon' => '⭐', 'required_streak' => 7, 'reward_exp' => 150],
            ['name' => 'Speed Learner', 'slug' => 'speed_learner', 'description' => 'Complete 10 lessons in a day', 'icon' => '⚡', 'required_lessons' => 10, 'reward_exp' => 400],
        ];
        
        foreach ($achievements as $achievement) {
            Achievement::create($achievement);
        }
        
        // Shop Items
        $shopItems = [
            ['name' => 'Extra Heart', 'description' => '+1 heart', 'type' => 'heart', 'price_coins' => 50, 'effects' => json_encode(['hearts' => 1])],
            ['name' => 'Full Hearts', 'description' => 'Refill all hearts', 'type' => 'heart', 'price_coins' => 150, 'effects' => json_encode(['hearts' => 'full'])],
            ['name' => 'XP Booster', 'description' => '2x EXP for 30 minutes', 'type' => 'booster', 'price_coins' => 200, 'effects' => json_encode(['xp_multiplier' => 2, 'duration_minutes' => 30])],
        ];
        
        foreach ($shopItems as $item) {
            ShopItem::create($item);
        }
    }
}