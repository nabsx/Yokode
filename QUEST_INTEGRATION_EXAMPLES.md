# 🔌 Daily Quest Templates - Integration Examples

## Introduction
File ini berisi contoh-contoh bagaimana mengintegrasikan Daily Quest Templates dengan kode aplikasi Anda.

## Table of Contents
1. [Controller Integration](#controller-integration)
2. [Command Integration](#command-integration)
3. [Service Integration](#service-integration)
4. [Event Integration](#event-integration)
5. [API Integration](#api-integration)
6. [View Integration](#view-integration)

---

## Controller Integration

### Contoh 1: Generate Quest di Dashboard Controller
```php
<?php

namespace App\Http\Controllers;

use App\Helpers\QuestHelper;
use App\Models\DailyQuest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get or generate today's quest
        $quest = QuestHelper::getTodayQuest();
        if (!$quest) {
            $quest = QuestHelper::generateDailyQuests();
        }
        
        // Get user's quest progress
        $userQuest = QuestHelper::getUserTodayQuest($user);
        
        return view('dashboard', compact('quest', 'userQuest'));
    }
}
```

### Contoh 2: Display Quest in View
```blade
<!-- Display Current Daily Quest -->
@if($quest && $userQuest)
    <div class="quest-card">
        <h3>{{ $quest->title }}</h3>
        <p>{{ $quest->description }}</p>
        
        <!-- Progress Bar -->
        <div class="progress">
            @php
                $percentage = ($userQuest->progress / $quest->target) * 100;
            @endphp
            <div class="progress-bar" style="width: {{ $percentage }}%"></div>
        </div>
        
        <p>{{ $userQuest->progress }} / {{ $quest->target }}</p>
        
        @if($userQuest->completed)
            <button class="claim-reward" onclick="claimReward({{ $userQuest->id }})">
                Claim Reward
            </button>
        @endif
    </div>
@endif
```

### Contoh 3: Update Quest Progress
```php
<?php

namespace App\Http\Controllers;

use App\Models\UserQuest;
use App\Helpers\QuestHelper;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function complete($lessonId)
    {
        $user = Auth::user();
        
        // Complete lesson logic...
        $lesson = Lesson::find($lessonId);
        $user->completeLesson($lesson);
        
        // Update quest progress
        $this->updateQuestProgress($user, 'complete_lesson', 1);
        
        return redirect()->back()->with('success', 'Lesson completed!');
    }
    
    private function updateQuestProgress($user, $questType, $amount = 1)
    {
        $todayQuest = QuestHelper::getUserTodayQuest($user);
        
        if ($todayQuest && $todayQuest->dailyQuest->type === $questType) {
            $todayQuest->progress += $amount;
            
            if ($todayQuest->progress >= $todayQuest->dailyQuest->target) {
                $todayQuest->progress = $todayQuest->dailyQuest->target;
                $todayQuest->completed = true;
                $todayQuest->completed_at = now();
            }
            
            $todayQuest->save();
        }
    }
}
```

### Contoh 4: Claim Rewards
```php
<?php

namespace App\Http\Controllers;

use App\Models\UserQuest;
use App\Events\QuestCompleted;
use Illuminate\Support\Facades\Auth;

class QuestController extends Controller
{
    public function claimReward($userQuestId)
    {
        $user = Auth::user();
        $userQuest = UserQuest::findOrFail($userQuestId);
        
        // Verify quest belongs to user
        if ($userQuest->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Verify quest is completed
        if (!$userQuest->completed) {
            return response()->json(['error' => 'Quest not completed'], 400);
        }
        
        $quest = $userQuest->dailyQuest;
        
        // Update user rewards
        $user->total_exp += $quest->reward_exp;
        $user->coins += $quest->reward_coins;
        $user->save();
        
        // Mark as claimed
        $userQuest->reward_claimed = true;
        $userQuest->reward_claimed_at = now();
        $userQuest->save();
        
        // Fire event
        event(new QuestCompleted($user, $userQuest));
        
        return response()->json([
            'success' => true,
            'message' => 'Rewards claimed!',
            'rewards' => [
                'exp' => $quest->reward_exp,
                'coins' => $quest->reward_coins,
            ]
        ]);
    }
}
```

---

## Command Integration

### Contoh 1: Custom Command untuk Generate & Notify
```php
<?php

namespace App\Console\Commands;

use App\Helpers\QuestHelper;
use App\Models\User;
use App\Notifications\NewQuestAvailable;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateDailyQuestsWithNotification extends Command
{
    protected $signature = 'quests:generate-with-notification {--date= : Date to generate for}';
    protected $description = 'Generate daily quests and notify users';

    public function handle()
    {
        $date = $this->option('date') 
            ? Carbon::createFromFormat('Y-m-d', $this->option('date')) 
            : Carbon::now();

        // Generate quests
        $quest = QuestHelper::generateDailyQuests($date);
        
        if (!$quest) {
            $this->error('No template found for this day');
            return;
        }

        $this->info("Quest generated: {$quest->title}");
        
        // Notify users
        $users = User::where('role', 'user')->get();
        foreach ($users as $user) {
            $user->notify(new NewQuestAvailable($quest));
        }
        
        $this->info("Notified {$users->count()} users");
    }
}
```

### Contoh 2: Command untuk Reset/Requeue Quests
```php
<?php

namespace App\Console\Commands;

use App\Models\UserQuest;
use App\Models\DailyQuest;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RequeueIncompleteQuests extends Command
{
    protected $signature = 'quests:requeue {--days=1 : Days to look back}';
    protected $description = 'Requeue incomplete quests for users';

    public function handle()
    {
        $days = $this->option('days');
        $date = Carbon::now()->subDays($days);
        
        // Get incomplete quests
        $incompleteQuests = UserQuest::where('completed', false)
            ->where('date', '<', $date)
            ->get();
        
        $this->info("Found {$incompleteQuests->count()} incomplete quests");
        
        if ($this->confirm('Requeue these quests for today?')) {
            foreach ($incompleteQuests as $userQuest) {
                $userQuest->date = Carbon::now();
                $userQuest->progress = 0;
                $userQuest->completed = false;
                $userQuest->completed_at = null;
                $userQuest->save();
            }
            
            $this->info("Requeued {$incompleteQuests->count()} quests!");
        }
    }
}
```

---

## Service Integration

### Contoh 1: Quest Service untuk Business Logic
```php
<?php

namespace App\Services;

use App\Models\User;
use App\Models\DailyQuest;
use App\Models\UserQuest;
use App\Helpers\QuestHelper;
use Carbon\Carbon;

class QuestService
{
    /**
     * Check user progress on daily quest
     */
    public function getUserQuestProgress(User $user): array
    {
        $userQuest = QuestHelper::getUserTodayQuest($user);
        
        if (!$userQuest) {
            return [
                'hasQuest' => false,
                'progress' => 0,
                'target' => 0,
            ];
        }
        
        return [
            'hasQuest' => true,
            'questId' => $userQuest->id,
            'questTitle' => $userQuest->dailyQuest->title,
            'progress' => $userQuest->progress,
            'target' => $userQuest->dailyQuest->target,
            'completed' => $userQuest->completed,
            'percentage' => ($userQuest->progress / $userQuest->dailyQuest->target) * 100,
        ];
    }

    /**
     * Update progress dari berbagai aktivitas
     */
    public function updateProgressFromActivity(User $user, string $activityType, int $amount = 1): void
    {
        $userQuest = QuestHelper::getUserTodayQuest($user);
        
        if (!$userQuest) {
            return;
        }
        
        $questType = $this->mapActivityToQuestType($activityType);
        
        if ($userQuest->dailyQuest->type === $questType) {
            $userQuest->progress = min(
                $userQuest->progress + $amount,
                $userQuest->dailyQuest->target
            );
            
            if ($userQuest->progress >= $userQuest->dailyQuest->target) {
                $userQuest->completed = true;
                $userQuest->completed_at = now();
            }
            
            $userQuest->save();
        }
    }

    /**
     * Get weekly quest summary
     */
    public function getWeeklyQuestSummary(User $user): array
    {
        $sevenDaysAgo = Carbon::now()->subDays(6);
        
        $quests = UserQuest::where('user_id', $user->id)
            ->where('date', '>=', $sevenDaysAgo)
            ->with('dailyQuest')
            ->orderBy('date')
            ->get();
        
        return $quests->map(function ($uq) {
            return [
                'date' => $uq->date,
                'title' => $uq->dailyQuest->title,
                'completed' => $uq->completed,
                'progress' => "{$uq->progress}/{$uq->dailyQuest->target}",
            ];
        })->toArray();
    }

    private function mapActivityToQuestType(string $activity): ?string
    {
        $mapping = [
            'lesson_completed' => 'complete_lesson',
            'quiz_answered' => 'answer_quiz',
            'exp_gained' => 'gain_exp',
            'user_login' => 'login',
            'perfect_quiz' => 'perfect_quiz',
        ];
        
        return $mapping[$activity] ?? null;
    }
}
```

### Contoh 2: Menggunakan Service di Controller
```php
<?php

namespace App\Http\Controllers;

use App\Services\QuestService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private QuestService $questService;

    public function __construct(QuestService $questService)
    {
        $this->questService = $questService;
    }

    public function index()
    {
        $user = Auth::user();
        
        $questProgress = $this->questService->getUserQuestProgress($user);
        $weeklyQuests = $this->questService->getWeeklyQuestSummary($user);
        
        return view('dashboard', compact('questProgress', 'weeklyQuests'));
    }
}
```

---

## Event Integration

### Contoh 1: Events untuk Quest Actions
```php
<?php

namespace App\Events;

use App\Models\User;
use App\Models\UserQuest;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuestCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public User $user,
        public UserQuest $userQuest
    ) {}
}

class QuestProgressUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public User $user,
        public UserQuest $userQuest,
        public int $newProgress
    ) {}
}

class QuestRewardClaimed
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public User $user,
        public UserQuest $userQuest,
        public int $expReward,
        public int $coinsReward
    ) {}
}
```

### Contoh 2: Event Listeners
```php
<?php

namespace App\Listeners;

use App\Events\QuestCompleted;
use App\Notifications\QuestCompletedNotification;

class SendQuestCompletedNotification
{
    public function handle(QuestCompleted $event)
    {
        $event->user->notify(new QuestCompletedNotification($event->userQuest));
    }
}

// Register in EventServiceProvider
protected $listen = [
    QuestCompleted::class => [
        SendQuestCompletedNotification::class,
        UpdateUserAchievements::class,
    ],
];
```

---

## API Integration

### Contoh 1: API Endpoint untuk Quest Info
```php
<?php

namespace App\Http\Controllers\Api;

use App\Helpers\QuestHelper;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class QuestController extends Controller
{
    /**
     * GET /api/quests/today
     */
    public function getToday()
    {
        $user = Auth::user();
        $quest = QuestHelper::getTodayQuest();
        
        if (!$quest) {
            return response()->json(['message' => 'No quest available'], 404);
        }
        
        $userQuest = QuestHelper::getUserTodayQuest($user);
        
        return response()->json([
            'id' => $quest->id,
            'title' => $quest->title,
            'description' => $quest->description,
            'type' => $quest->type,
            'target' => $quest->target,
            'rewards' => [
                'exp' => $quest->reward_exp,
                'coins' => $quest->reward_coins,
            ],
            'progress' => [
                'current' => $userQuest?->progress ?? 0,
                'target' => $quest->target,
                'completed' => $userQuest?->completed ?? false,
            ]
        ]);
    }

    /**
     * POST /api/quests/{id}/progress
     */
    public function updateProgress($userQuestId)
    {
        $user = Auth::user();
        $userQuest = $user->userQuests()->findOrFail($userQuestId);
        
        $userQuest->progress = request('progress');
        $userQuest->save();
        
        return response()->json([
            'success' => true,
            'progress' => $userQuest->progress,
            'completed' => $userQuest->completed,
        ]);
    }

    /**
     * POST /api/quests/{id}/claim
     */
    public function claimReward($userQuestId)
    {
        $user = Auth::user();
        $userQuest = $user->userQuests()->findOrFail($userQuestId);
        
        if (!$userQuest->completed) {
            return response()->json(['error' => 'Quest not completed'], 400);
        }
        
        $quest = $userQuest->dailyQuest;
        
        $user->total_exp += $quest->reward_exp;
        $user->coins += $quest->reward_coins;
        $user->save();
        
        $userQuest->reward_claimed = true;
        $userQuest->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Rewards claimed',
            'rewards' => [
                'exp' => $quest->reward_exp,
                'coins' => $quest->reward_coins,
            ]
        ]);
    }

    /**
     * GET /api/quests/weekly
     */
    public function getWeekly()
    {
        $user = Auth::user();
        $sevenDaysAgo = \Carbon\Carbon::now()->subDays(6);
        
        $quests = $user->userQuests()
            ->where('date', '>=', $sevenDaysAgo)
            ->with('dailyQuest')
            ->orderBy('date')
            ->get();
        
        return response()->json([
            'quests' => $quests->map(function ($uq) {
                return [
                    'date' => $uq->date,
                    'title' => $uq->dailyQuest->title,
                    'completed' => $uq->completed,
                    'progress' => $uq->progress,
                    'target' => $uq->dailyQuest->target,
                ];
            })
        ]);
    }
}

// Routes
Route::middleware('auth:sanctum')->prefix('api')->group(function () {
    Route::get('/quests/today', [QuestController::class, 'getToday']);
    Route::post('/quests/{id}/progress', [QuestController::class, 'updateProgress']);
    Route::post('/quests/{id}/claim', [QuestController::class, 'claimReward']);
    Route::get('/quests/weekly', [QuestController::class, 'getWeekly']);
});
```

---

## View Integration

### Contoh 1: Quest Widget Component
```blade
<!-- resources/views/components/quest-widget.blade.php -->
@php
    use App\Helpers\QuestHelper;
    $quest = QuestHelper::getTodayQuest();
    $userQuest = Auth::user() ? QuestHelper::getUserTodayQuest(Auth::user()) : null;
@endphp

@if($quest && $userQuest)
    <div class="quest-widget">
        <div class="quest-header">
            <h4>Daily Quest</h4>
            <span class="quest-type badge">{{ $quest->type }}</span>
        </div>
        
        <div class="quest-content">
            <h5>{{ $quest->title }}</h5>
            <p>{{ $quest->description }}</p>
            
            <div class="progress-section">
                <div class="progress-bar">
                    @php
                        $percentage = ($userQuest->progress / $quest->target) * 100;
                    @endphp
                    <div class="progress-fill" style="width: {{ $percentage }}%"></div>
                </div>
                <p class="progress-text">{{ $userQuest->progress }} / {{ $quest->target }}</p>
            </div>
            
            <div class="rewards">
                <span class="reward exp">{{ $quest->reward_exp }} XP</span>
                <span class="reward coins">{{ $quest->reward_coins }} 🪙</span>
            </div>
            
            @if($userQuest->completed)
                <button class="btn-claim" onclick="claimReward({{ $userQuest->id }})">
                    Claim Reward
                </button>
            @endif
        </div>
    </div>
@endif
```

### Contoh 2: Menggunakan Component
```blade
<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="dashboard">
        <x-quest-widget />
        
        <!-- Other dashboard content -->
    </div>
@endsection
```

---

## Testing Integration

### Contoh: Testing Daily Quest Feature
```php
<?php

namespace Tests\Feature;

use App\Helpers\QuestHelper;
use App\Models\User;
use App\Models\DailyQuestTemplate;
use Carbon\Carbon;
use Tests\TestCase;

class DailyQuestTest extends TestCase
{
    public function test_can_generate_daily_quest()
    {
        DailyQuestTemplate::create([
            'day_of_week' => 1, // Monday
            'title' => 'Test Quest',
            'description' => 'Test',
            'type' => 'login',
            'target' => 1,
            'reward_exp' => 50,
            'reward_coins' => 25,
        ]);

        $quest = QuestHelper::generateDailyQuests();
        
        $this->assertNotNull($quest);
        $this->assertEquals('Test Quest', $quest->title);
    }

    public function test_user_receives_quest()
    {
        $user = User::factory()->create();
        
        QuestHelper::generateDailyQuests();
        
        $userQuest = QuestHelper::getUserTodayQuest($user);
        
        $this->assertNotNull($userQuest);
        $this->assertFalse($userQuest->completed);
    }

    public function test_can_update_quest_progress()
    {
        $user = User::factory()->create();
        $userQuest = QuestHelper::getUserTodayQuest($user);
        
        $userQuest->progress = 5;
        $userQuest->save();
        
        $this->assertEquals(5, $userQuest->fresh()->progress);
    }

    public function test_can_claim_reward()
    {
        $user = User::factory()->create();
        $userQuest = QuestHelper::getUserTodayQuest($user);
        
        $userQuest->completed = true;
        $userQuest->save();
        
        $initialExp = $user->total_exp;
        
        $user->total_exp += $userQuest->dailyQuest->reward_exp;
        $user->save();
        
        $this->assertGreater($user->total_exp, $initialExp);
    }
}
```

---

## Summary
Fitur Daily Quest Templates dapat diintegrasikan dengan:
- ✅ Controllers (untuk logic bisnis)
- ✅ Commands (untuk automation)
- ✅ Services (untuk reusable logic)
- ✅ Events (untuk reactive programming)
- ✅ APIs (untuk mobile/external apps)
- ✅ Views (untuk UI)
- ✅ Tests (untuk quality assurance)

Gunakan contoh-contoh di atas sebagai referensi untuk implementasi Anda!
