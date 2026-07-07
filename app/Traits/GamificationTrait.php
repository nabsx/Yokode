<?php


namespace App\Traits;

use App\Models\UserHeart;
use App\Models\UserStreak;
use App\Models\UserAchievement;
use App\Models\Achievement;
use App\Models\UserBooster;
use App\Models\UserQuest;      // TAMBAHKAN INI
use App\Models\DailyQuest;     // TAMBAHKAN INI (opsional)
use Carbon\Carbon;

trait GamificationTrait
{
    // ==================== HEARTS SYSTEM ====================
    public function getHeartsAttribute()
    {
        $hearts = UserHeart::firstOrCreate(
            ['user_id' => $this->id],
            ['current_hearts' => 5, 'max_hearts' => 5, 'last_recharge_at' => now()]
        );
        
        // Auto recharge setiap 30 menit
        if ($hearts->last_recharge_at && $hearts->current_hearts < $hearts->max_hearts) {
            $minutesPassed = Carbon::parse($hearts->last_recharge_at)->diffInMinutes(now());
            $rechargeCount = floor($minutesPassed / 30);
            if ($rechargeCount > 0) {
                $newHearts = min($hearts->max_hearts, $hearts->current_hearts + $rechargeCount);
                $hearts->update([
                    'current_hearts' => $newHearts,
                    'last_recharge_at' => now()
                ]);
            }
        }
        
        return $hearts;
    }
    
    public function loseHeart(): bool
    {
        $hearts = $this->hearts;
        if ($hearts->current_hearts <= 0) {
            return false;
        }
        
        $hearts->update([
            'current_hearts' => $hearts->current_hearts - 1,
            'last_recharge_at' => now()
        ]);
        
        return true;
    }
    
    public function addHeart(int $amount = 1): void
    {
        $hearts = $this->hearts;
        $hearts->update([
            'current_hearts' => min($hearts->max_hearts, $hearts->current_hearts + $amount)
        ]);
    }
    
    public function refillHearts(): void
    {
        $hearts = $this->hearts;
        $hearts->update([
            'current_hearts' => $hearts->max_hearts,
            'last_recharge_at' => now()
        ]);
    }
    
    // ==================== STREAK SYSTEM ====================
    public function getStreakAttribute()
    {
            $streak = UserStreak::firstOrCreate(
        ['user_id' => $this->id],
        ['current_streak' => 0, 'longest_streak' => 0, 'last_login_date' => null]  // Tambahkan last_login_date
    );
        
        $today = Carbon::today();
        $lastLogin = $streak->last_login_date ? Carbon::parse($streak->last_login_date) : null;
        
        if (!$lastLogin) {
            // First login
            $streak->update(['current_streak' => 1, 'last_login_date' => $today]);
        } elseif ($lastLogin->eq($today)) {
            // Already logged in today
        } elseif ($lastLogin->eq($today->copy()->subDay())) {
            // Streak continues
            $streak->increment('current_streak');
            if ($streak->current_streak > $streak->longest_streak) {
                $streak->update(['longest_streak' => $streak->current_streak]);
            }
            $streak->update(['last_login_date' => $today]);
            
            // Bonus streak
            $this->checkStreakBonus($streak->current_streak);
        } else {
            // Streak broken
            $streak->update(['current_streak' => 1, 'last_login_date' => $today]);
        }
        
        return $streak;
    }
    
    private function checkStreakBonus(int $streakCount): void
    {
        $bonusDays = [7, 30, 100, 365];
        
        if (in_array($streakCount, $bonusDays)) {
            $bonusExp = 100 * ($streakCount / 7);
            $this->addExp((int)$bonusExp);
            $this->addCoins(50);
        }
    }
    
    // ==================== XP BOOSTER ====================
    public function getExpMultiplierAttribute(): float
    {
        $booster = UserBooster::where('user_id', $this->id)
            ->where('type', 'xp_2x')
            ->where('expires_at', '>', now())
            ->first();
            
        return $booster ? 2.0 : 1.0;
    }
    
    public function activateBooster(string $type, int $durationMinutes = 30): void
    {
        UserBooster::create([
            'user_id' => $this->id,
            'type' => $type,
            'expires_at' => now()->addMinutes($durationMinutes)
        ]);
    }
    
    // ==================== ACHIEVEMENTS ====================
    public function checkAchievements(): void
    {
        $achievements = Achievement::all();
        $completedLessons = $this->completed_lessons_count;
        $totalCorrectAnswers = $this->answers()->where('is_correct', true)->count();
        
        foreach ($achievements as $achievement) {
            $hasAchievement = UserAchievement::where('user_id', $this->id)
                ->where('achievement_id', $achievement->id)
                ->exists();
                
            if ($hasAchievement) {
                continue;
            }
            
            $meetsRequirement = true;
            
            if ($achievement->required_lessons && $completedLessons < $achievement->required_lessons) {
                $meetsRequirement = false;
            }
            
            if ($achievement->required_quizzes && $totalCorrectAnswers < $achievement->required_quizzes) {
                $meetsRequirement = false;
            }
            
            if ($achievement->required_exp && $this->total_exp < $achievement->required_exp) {
                $meetsRequirement = false;
            }
            
            if ($achievement->required_streak && $this->streak->current_streak < $achievement->required_streak) {
                $meetsRequirement = false;
            }
            
            if ($meetsRequirement) {
                UserAchievement::create([
                    'user_id' => $this->id,
                    'achievement_id' => $achievement->id,
                    'earned_at' => now()
                ]);
                
                $this->addExp($achievement->reward_exp);
                $this->addCoins($achievement->reward_coins);
            }
        }
    }
    
    // ==================== LEAGUE SYSTEM ====================
    public function getLeagueAttribute(): string
    {
        $leagues = ['Bronze', 'Silver', 'Gold', 'Sapphire', 'Ruby', 'Pearl', 'Obsidian', 'Legend'];
        $expNeeded = [0, 500, 1200, 2100, 3200, 4500, 6000, 8000];
        
        $level = 0;
        foreach ($expNeeded as $index => $need) {
            if ($this->total_exp >= $need) {
                $level = $index;
            }
        }
        
        return $leagues[$level] ?? 'Bronze';
    }
    
    public function getLeagueProgressAttribute(): int
    {
        $expNeeded = [500, 1200, 2100, 3200, 4500, 6000, 8000];
        $currentLeague = array_search($this->league, ['Bronze', 'Silver', 'Gold', 'Sapphire', 'Ruby', 'Pearl', 'Obsidian', 'Legend']);
        
        if ($currentLeague >= count($expNeeded)) {
            return 100;
        }
        
        $prevExp = $currentLeague > 0 ? $expNeeded[$currentLeague - 1] : 0;
        $nextExp = $expNeeded[$currentLeague];
        $expInLeague = $this->total_exp - $prevExp;
        $expNeededForNext = $nextExp - $prevExp;
        
        return min(100, round(($expInLeague / $expNeededForNext) * 100));
    }
}