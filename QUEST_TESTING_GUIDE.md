# 🧪 Quest Template - Testing Guide

## Step 1: Setup & Initialization

### 1.1 Run Migration
```bash
php artisan migrate
```

### 1.2 Initialize Templates
```bash
# Via seeder
php artisan db:seed --class=DailyQuestTemplateSeeder

# Verify: should show 7 templates
php artisan tinker
> App\Models\DailyQuestTemplate::count()
# Output: 7
```

### 1.3 Generate Daily Quest
```bash
php artisan quests:generate

# Should output:
# ✓ Daily quest created successfully!
#   Template: [Quest Title]
#   Assigned to: [Number] users
```

---

## Step 2: Verify Templates Created

### Check Templates in Database
```bash
php artisan tinker

# Check all templates
> $templates = App\Models\DailyQuestTemplate::all();
> foreach($templates as $t) { echo "Day {$t->day_of_week}: {$t->title}\n"; }

# Expected output:
# Day 0: Login Challenge
# Day 1: Lesson Completion
# Day 2: Quiz Master
# Day 3: Experience Grinder
# Day 4: Perfect Score
# Day 5: Lesson Marathon
# Day 6: Weekend Warrior
```

---

## Step 3: Test Daily Quest Generation

### Test Manual Generation
```bash
php artisan tinker

# Get template for today
> $today = now()
> $dayOfWeek = $today->dayOfWeek
> $template = App\Models\DailyQuestTemplate::where('day_of_week', $dayOfWeek)->first()
> echo "Today ({$today->format('l')}): {$template->title}\n"

# Check if daily quest created
> $quest = App\Models\DailyQuest::where('date', $today->toDateString())->first()
> if ($quest) { echo "✓ DailyQuest created: {$quest->title}\n"; } else { echo "✗ No quest\n"; }

# Check user assignments
> $userCount = $quest->userQuests()->count()
> echo "Assigned to: $userCount users\n"
```

---

## Step 4: Test User Dashboard

### 4.1 Login as User
- Open app: http://localhost:8000
- Login with any user account

### 4.2 Check Dashboard
- Go to `/dashboard`
- Look for "📅 Misi Harian" section
- Should show today's quest:
  - Title
  - Description
  - Progress bar (0/target)
  - Rewards (XP + Coins)

### 4.3 Expected Appearance
```
📅 Misi Harian                                0/3 Selesai

📌 Login Challenge
   Masuk ke aplikasi hari ini
   ⭐ +50 EXP    🪙 +25 Koin
   [====          ] 0/1
```

---

## Step 5: Test Progress Tracking

### 5.1 Manual Progress Update
```bash
php artisan tinker

# Get user
> $user = App\Models\User::find(1)
> echo "Before - Coins: {$user->coins}, XP: {$user->total_exp}\n"

# Update progress (simulate complete_lesson)
> $user->updateQuestProgress('complete_lesson', 1)

# Check progress
> $userQuest = App\Models\UserQuest::where('user_id', 1)->where('date', now()->toDateString())->first()
> echo "Quest Progress: {$userQuest->progress}/{$userQuest->dailyQuest->target}\n"
> echo "Completed: " . ($userQuest->completed ? 'Yes' : 'No') . "\n"

# Refresh and check rewards
> $user->refresh()
> echo "After - Coins: {$user->coins}, XP: {$user->total_exp}\n"
```

### 5.2 Check Quest Completion UI
```bash
# If progress >= target:
> if ($userQuest->progress >= $userQuest->dailyQuest->target) {
    echo "✓ Quest should show as COMPLETED\n";
    echo "Rewards: +{$userQuest->dailyQuest->reward_exp} XP, +{$userQuest->dailyQuest->reward_coins} Coins\n";
}
```

### 5.3 Visual Check in Dashboard
- Refresh dashboard
- Completed quest should show:
  - Green background
  - ✅ checkmark instead of 📌
  - Progress: 1/1 or similar (100%)
  - Green status: ✓ Selesai

---

## Step 6: Test Multiple Users

### 6.1 Create Test Users
```bash
php artisan tinker

# Create 3 test users
> $user2 = App\Models\User::create([
    'name' => 'Test User 2',
    'email' => 'test2@example.com',
    'password' => Hash::make('password'),
    'is_active' => true
])

> $user3 = App\Models\User::create([
    'name' => 'Test User 3',
    'email' => 'test3@example.com',
    'password' => Hash::make('password'),
    'is_active' => true
])

# Verify quests assigned
> App\Models\UserQuest::where('date', now()->toDateString())->count()
# Should be >= 3
```

### 6.2 Test Different Users
- Login as different users
- Each should see same quest (from template)
- But independent progress tracking

---

## Step 7: Test Daily Quest Generation Automation

### 7.1 Test Scheduler (Manual)
```bash
# Manually trigger scheduler
php artisan schedule:run

# Check if quests:generate ran
tail -f storage/logs/laravel.log | grep quests:generate
```

### 7.2 Test with Different Date
```bash
# Generate quest for tomorrow
php artisan quests:generate --date=tomorrow

# Or specific date
php artisan quests:generate --date=2026-07-15

# Verify
php artisan tinker
> App\Models\DailyQuest::where('date', '2026-07-15')->first()
```

### 7.3 Test Multiple Days
```bash
# Generate for 7 days
php artisan tinker
> for ($i = 0; $i < 7; $i++) {
    $date = now()->addDays($i)->toDateString();
    \Illuminate\Support\Facades\Artisan::call('quests:generate', ['--date' => $date]);
    $count = App\Models\DailyQuest::where('date', $date)->count();
    echo "Date: $date - Quests: $count\n";
  }
```

---

## Step 8: Test Admin Panel

### 8.1 Access Admin Panel
- Login as admin
- Go to `/admin/quest-templates`

### 8.2 Verify Templates Display
Should see 7 color-coded cards:
- Card 1 (Sunday): Red - Login Challenge
- Card 2 (Monday): Blue - Lesson Completion
- Card 3 (Tuesday): Purple - Quiz Master
- etc.

Each card shows:
- Day name
- Quest title
- Description
- Type
- Target
- Rewards (XP + Coins)
- "Edit" button

### 8.3 Test Edit Template
1. Click "Edit" on any template
2. Change fields:
   - Title: "New Title"
   - Target: Change to different number
   - Rewards: Increase coins/exp
3. Click "Save"
4. Go back to templates list
5. Verify changes saved
6. Important: existing quests won't change, only new quests tomorrow

### 8.4 Test Live Preview
- While editing, changes should show in preview section
- Type new title → preview updates

---

## Step 9: Test Reward Distribution

### 9.1 Complete a Real Lesson
1. Login as user
2. Start a lesson (any category)
3. Complete the lesson
4. Check dashboard:
   - "Selesaikan 1 Modul" quest should increase progress
   - If target reached → completed & rewards given
   - Check coins/XP increased

### 9.2 Answer Quiz
1. In a lesson, answer quiz questions
2. Check dashboard:
   - "Jawab Quiz" quest should track answers
   - Progress increases per answer

### 9.3 Verify Rewards
```bash
php artisan tinker

# Track user rewards
> $user = App\Models\User::find(1)
> echo "Current XP: {$user->total_exp}\n"
> echo "Current Coins: {$user->coins}\n"

# Manually complete a quest
> $userQuest = App\Models\UserQuest::where('user_id', 1)->first()
> echo "Quest rewards: {$userQuest->dailyQuest->reward_exp} XP, {$userQuest->dailyQuest->reward_coins} coins\n"
```

---

## Step 10: Test Fallback Scenarios

### 10.1 No Template for Day
```bash
php artisan tinker

# Delete one template
> App\Models\DailyQuestTemplate::where('day_of_week', 1)->delete()

# Try to generate quest for Monday
php artisan quests:generate --date=2026-07-14

# Should create fallback "Login Challenge" quest
```

### 10.2 No Daily Quest Yet
```bash
php artisan tinker

# Delete today's quests
> App\Models\DailyQuest::where('date', now()->toDateString())->delete()

# New user login or dashboard refresh
# Should auto-create from template
```

---

## Step 11: Performance Testing

### 11.1 Test with Many Users
```bash
php artisan tinker

# Create 100 test users
> factory(\App\Models\User::class, 100)->create()

# Measure time to generate quests
> $start = microtime(true)
> \Illuminate\Support\Facades\Artisan::call('quests:generate')
> $elapsed = microtime(true) - $start
> echo "Generated in: {$elapsed} seconds\n"

# Check database
> App\Models\UserQuest::where('date', now()->toDateString())->count()
# Should be ~100 per quest
```

### 11.2 Check Query Performance
```bash
php artisan tinker
> DB::enableQueryLog()
> $user = App\Models\User::find(1)
> $user->createDailyQuests()
> $queries = DB::getQueryLog()
> echo "Queries executed: " . count($queries) . "\n"
```

---

## Step 12: Test Reset & Recovery

### 12.1 Reset Daily Quests
```bash
php artisan tinker

# Delete all
> App\Models\UserQuest::truncate()
> App\Models\DailyQuest::truncate()

# Regenerate
php artisan quests:generate

# Verify
> App\Models\DailyQuest::where('date', now()->toDateString())->count()
> App\Models\UserQuest::where('date', now()->toDateString())->count()
```

### 12.2 Reinitialize Templates
```bash
php artisan tinker

# Delete and re-create
> App\Models\DailyQuestTemplate::truncate()
php artisan db:seed --class=DailyQuestTemplateSeeder

# Verify
> App\Models\DailyQuestTemplate::count()
# Should be 7
```

---

## Checklist for Production

- [ ] Templates initialized (7 templates exist)
- [ ] Daily quest generation works
- [ ] Users see quests in dashboard
- [ ] Progress tracking works
- [ ] Rewards given correctly
- [ ] Admin can edit templates
- [ ] Scheduler configured on server
- [ ] Fallback scenarios tested
- [ ] Multiple users tested
- [ ] Performance acceptable
- [ ] Error logging working
- [ ] Database backups scheduled

---

## Common Test Cases

### Test Case 1: New User Signs Up
```
1. Create new user via registration
2. Auto-create user in database
3. Dashboard should show today's quest
4. Quest from template of current day
5. Progress tracking starts at 0
```

### Test Case 2: Template Changed
```
1. Admin edits Monday template
2. Existing Monday quest unchanged (already created)
3. Next Monday quest uses new template
4. Current week users unaffected
```

### Test Case 3: User Completes Quest
```
1. Progress reaches target
2. Quest marked completed
3. Rewards given (XP + Coins)
4. UI shows completed status
5. Quest counts as "Selesai"
```

### Test Case 4: Multiple Quests Same Day
```
1. System should generate 1 quest per template
2. User assigned to all quests
3. Each quest tracked independently
4. Rewards given per quest completion
```

---

## Debugging During Testing

Enable debug mode:
```bash
# In .env
APP_DEBUG=true
```

Check logs:
```bash
tail -f storage/logs/laravel.log
```

Database inspection:
```bash
# List all tables
php artisan tinker
> DB::select('SHOW TABLES')

# Show schema
> DB::select('DESCRIBE daily_quest_templates')
```

---

## Performance Benchmarks

Expected metrics:
- Template generation: < 100ms
- Quest assignment to 1000 users: < 5 seconds
- Dashboard load: < 500ms
- Reward distribution: < 50ms per update

If slower, check:
- Database indexes
- Query optimization
- Cache configuration
