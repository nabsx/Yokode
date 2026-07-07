<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\GamificationTrait;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable, GamificationTrait;

    protected $fillable = [
        'name',
        'email',
        'password',
        'total_exp',
        'is_premium',
        'premium_expires_at',
        'coins',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_premium' => 'boolean',
            'premium_expires_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($user) {
            $user->total_exp = $user->total_exp ?? 0;
            $user->is_premium = $user->is_premium ?? false;
            $user->coins = $user->coins ?? 0;
        });

        static::created(function ($user) {
            UserHeart::firstOrCreate(['user_id' => $user->id]);
            UserStreak::firstOrCreate(['user_id' => $user->id]);
            $user->createDailyQuests();
        });
    }

    // ==================== RELATIONS ====================
    public function progresses()
    {
        return $this->hasMany(UserProgress::class);
    }

    public function answers()
    {
        return $this->hasMany(UserAnswer::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function hearts()
    {
        return $this->hasOne(UserHeart::class);
    }

    public function streak()
    {
        return $this->hasOne(UserStreak::class);
    }

    public function userQuests()
    {
        return $this->hasMany(UserQuest::class);
    }

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    public function boosters()
    {
        return $this->hasMany(UserBooster::class);
    }

    public function inventory()
    {
        return $this->hasMany(UserInventory::class);
    }

    // ==================== GAMIFIKASI METHODS ====================
    
    public function createDailyQuests()
    {
        $today = now()->toDateString();
        
        $exists = UserQuest::where('user_id', $this->id)
            ->where('date', $today)
            ->exists();
            
        if ($exists) {
            return;
        }
        
        $dailyQuests = DailyQuest::where('date', $today)->get();
        
        if ($dailyQuests->isEmpty()) {
            $defaultQuests = [
                ['title' => 'Selesaikan 1 Modul', 'description' => 'Selesaikan 1 modul belajar', 'type' => 'complete_lesson', 'target' => 1, 'reward_exp' => 50, 'reward_coins' => 10, 'date' => $today],
                ['title' => 'Jawab 5 Kuis', 'description' => 'Jawab 5 pertanyaan kuis', 'type' => 'answer_quiz', 'target' => 5, 'reward_exp' => 30, 'reward_coins' => 5, 'date' => $today],
                ['title' => 'Dapatkan 100 EXP', 'description' => 'Kumpulkan 100 EXP hari ini', 'type' => 'gain_exp', 'target' => 100, 'reward_exp' => 60, 'reward_coins' => 15, 'date' => $today],
            ];
            
            foreach ($defaultQuests as $quest) {
                $dailyQuest = DailyQuest::create($quest);
                UserQuest::create([
                    'user_id' => $this->id,
                    'daily_quest_id' => $dailyQuest->id,
                    'date' => $today,
                    'progress' => 0
                ]);
            }
        } else {
            foreach ($dailyQuests as $quest) {
                UserQuest::create([
                    'user_id' => $this->id,
                    'daily_quest_id' => $quest->id,
                    'date' => $today,
                    'progress' => 0
                ]);
            }
        }
    }
    
    public function updateQuestProgress(string $type, int $increment = 1)
    {
        $today = now()->toDateString();
        
        $userQuests = UserQuest::where('user_id', $this->id)
            ->where('date', $today)
            ->whereHas('dailyQuest', function ($q) use ($type) {
                $q->where('type', $type);
            })
            ->get();
            
        foreach ($userQuests as $userQuest) {
            if (!$userQuest->completed) {
                $userQuest->increment('progress', $increment);
                
                if ($userQuest->progress >= $userQuest->dailyQuest->target) {
                    $userQuest->update([
                        'completed' => true,
                        'completed_at' => now()
                    ]);
                    
                    $this->addExp($userQuest->dailyQuest->reward_exp);
                    $this->addCoins($userQuest->dailyQuest->reward_coins);
                }
            }
        }
    }
    
    public function addCoins(int $amount): self
    {
        $this->coins += $amount;
        $this->save();
        return $this;
    }
    
    public function deductCoins(int $amount): bool
    {
        if ($this->coins < $amount) {
            return false;
        }
        $this->coins -= $amount;
        $this->save();
        return true;
    }
    
    public function addHeart(int $amount = 1): void
    {
        $hearts = $this->hearts;
        $hearts->update([
            'current_hearts' => min($hearts->max_hearts, $hearts->current_hearts + $amount)
        ]);
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
    
    public function refillHearts(): void
    {
        $hearts = $this->hearts;
        $hearts->update([
            'current_hearts' => $hearts->max_hearts,
            'last_recharge_at' => now()
        ]);
    }
    
    public function activateBooster(string $type, int $durationMinutes = 30): void
    {
        UserBooster::create([
            'user_id' => $this->id,
            'type' => $type,
            'expires_at' => now()->addMinutes($durationMinutes)
        ]);
    }
    
    public function addExp(int $amount): self
    {
        $multiplier = $this->exp_multiplier;
        $finalAmount = $amount * $multiplier;
        
        $this->total_exp += $finalAmount;
        $this->save();
        
        $this->updateQuestProgress('gain_exp', $finalAmount);
        $this->checkAchievements();
        
        return $this;
    }
    
    // ==================== LEVEL METHODS ====================
    public function getLevelAttribute(): int
    {
        return floor($this->total_exp / 100) + 1;
    }
    
    public function getExpToNextLevelAttribute(): int
    {
        $currentLevel = $this->level;
        $expForCurrentLevel = ($currentLevel - 1) * 100;
        return 100 - ($this->total_exp - $expForCurrentLevel);
    }
    
    public function getLevelProgressAttribute(): int
    {
        $expInCurrentLevel = $this->total_exp % 100;
        return min(100, round(($expInCurrentLevel / 100) * 100));
    }
    
    public function getCompletedLessonsCountAttribute(): int
    {
        return $this->progresses()->where('completed', true)->count();
    }
    
    public function getIsPremiumActiveAttribute(): bool
    {
        if (!$this->is_premium) return false;
        if ($this->premium_expires_at && $this->premium_expires_at->isPast()) return false;
        return true;
    }
    
    public function activatePremium(int $days = 30): self
    {
        $this->is_premium = true;
        if ($this->premium_expires_at && $this->premium_expires_at->isFuture()) {
            $this->premium_expires_at = $this->premium_expires_at->addDays($days);
        } else {
            $this->premium_expires_at = now()->addDays($days);
        }
        $this->save();
        return $this;
    }
    
    public function deactivatePremium(): self
    {
        $this->is_premium = false;
        $this->premium_expires_at = null;
        $this->save();
        return $this;
    }
    
    public function hasCompletedLesson(Lesson $lesson): bool
    {
        return $this->progresses()
            ->where('lesson_id', $lesson->id)
            ->where('completed', true)
            ->exists();
    }
    
    public function checkAchievements()
    {
        $achievements = Achievement::all();
        $completedLessons = $this->completed_lessons_count;
        $totalCorrectAnswers = $this->answers()->where('is_correct', true)->count();
        
        foreach ($achievements as $achievement) {
            $has = UserAchievement::where('user_id', $this->id)
                ->where('achievement_id', $achievement->id)
                ->exists();
                
            if ($has) continue;
            
            $meets = true;
            if ($achievement->required_lessons && $completedLessons < $achievement->required_lessons) $meets = false;
            if ($achievement->required_quizzes && $totalCorrectAnswers < $achievement->required_quizzes) $meets = false;
            if ($achievement->required_exp && $this->total_exp < $achievement->required_exp) $meets = false;
            if ($achievement->required_streak && $this->streak->current_streak < $achievement->required_streak) $meets = false;
            
            if ($meets) {
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
    
    public function getLeagueAttribute(): string
    {
        $leagues = ['Bronze', 'Silver', 'Gold', 'Sapphire', 'Ruby', 'Pearl', 'Obsidian', 'Legend'];
        $expNeeded = [0, 500, 1200, 2100, 3200, 4500, 6000, 8000];
        $level = 0;
        foreach ($expNeeded as $index => $need) {
            if ($this->total_exp >= $need) $level = $index;
        }
        return $leagues[$level] ?? 'Bronze';
    }
    
    public function getLeagueProgressAttribute(): int
    {
        $expNeeded = [500, 1200, 2100, 3200, 4500, 6000, 8000];
        $leagues = ['Bronze', 'Silver', 'Gold', 'Sapphire', 'Ruby', 'Pearl', 'Obsidian', 'Legend'];
        $currentIndex = array_search($this->league, $leagues);
        
        if ($currentIndex >= count($expNeeded)) return 100;
        
        $prevExp = $currentIndex > 0 ? $expNeeded[$currentIndex - 1] : 0;
        $nextExp = $expNeeded[$currentIndex];
        $expInLeague = $this->total_exp - $prevExp;
        $expNeededForNext = $nextExp - $prevExp;
        
        return min(100, round(($expInLeague / $expNeededForNext) * 100));
    }
    
    public function getExpMultiplierAttribute(): float
    {
        $booster = UserBooster::where('user_id', $this->id)
            ->where('type', 'xp_2x')
            ->where('expires_at', '>', now())
            ->first();
        return $booster ? 2.0 : 1.0;
    }
    
    public function update(array $attributes = [], array $options = [])
    {
        return parent::update($attributes, $options);
    }
    
    public function scopeOrderByExp($query, string $direction = 'desc')
    {
        return $query->orderBy('total_exp', $direction);
    }
    
    public function scopePremiumActive($query)
    {
        return $query->where('is_premium', true)
            ->where(function ($q) {
                $q->whereNull('premium_expires_at')->orWhere('premium_expires_at', '>', now());
            });
    }
}