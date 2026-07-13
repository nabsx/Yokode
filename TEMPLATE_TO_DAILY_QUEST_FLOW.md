# Template → Daily Quest Flow Documentation

## 🎯 Bagaimana Template Menjadi Daily Quest?

### Alur Singkat

```
DailyQuestTemplate (Template/Blueprint di database)
           ↓
    Admin setup di panel
           ↓
   Scheduler / Command
           ↓
    GenerateDailyQuests
           ↓
  DailyQuest (Quest aktual untuk hari ini)
           ↓
    Assign ke setiap user
           ↓
   UserQuest (Tracking progress user)
```

---

## 📊 Detailed Flow

### 1. Admin Setup Template (Once)

**Location**: Admin Panel → `/admin/quest-templates`

Admin mengatur template untuk setiap hari:
- **Day of Week**: 0-6 (Sunday-Saturday)
- **Title**: Nama quest (e.g., "Login Challenge")
- **Description**: Penjelasan quest
- **Type**: Jenis quest (login, complete_lesson, answer_quiz, gain_exp, perfect_quiz)
- **Target**: Target yang harus dicapai (e.g., 5 quizzes)
- **Rewards**: XP dan coins untuk reward

```php
// Database: daily_quest_templates table
├── id
├── day_of_week (0 = Sunday, 6 = Saturday)
├── title
├── description
├── type
├── target
├── reward_exp
├── reward_coins
└── timestamps
```

### 2. Command Execution (Daily)

**Via Console Command**:
```bash
php artisan quests:generate
```

**Via Scheduler** (Automatic):
```php
// app/Console/Kernel.php
$schedule->command('quests:generate')->dailyAt('00:00');
```

### 3. GenerateDailyQuests Command

**File**: `app/Console/Commands/GenerateDailyQuests.php`

**Proses**:
1. Get current date (or specified date via `--date` option)
2. Determine day of week (0-6)
3. Find matching template from `daily_quest_templates`
4. Create new `DailyQuest` record:
   - Copy data dari template
   - Set `date` = hari ini
5. Assign ke semua active users (create `UserQuest` records)

```php
// Pseudo-code
$date = Carbon::now(); // atau --date=2026-07-15
$dayOfWeek = $date->format('w'); // 0-6

$template = DailyQuestTemplate::where('day_of_week', $dayOfWeek)->first();

$dailyQuest = DailyQuest::create([
    'title' => $template->title,
    'description' => $template->description,
    'type' => $template->type,
    'target' => $template->target,
    'reward_exp' => $template->reward_exp,
    'reward_coins' => $template->reward_coins,
    'date' => $date, // TODAY
]);

// Assign ke semua users
$users = User::where('role', 'user')->get();
foreach ($users as $user) {
    UserQuest::create([
        'user_id' => $user->id,
        'daily_quest_id' => $dailyQuest->id,
        'progress' => 0,
        'completed' => false,
        'date' => $date,
    ]);
}
```

### 4. User Receives Quest

**Database Relationship**:
```
User (1)
  └─ UserQuest (many)
       └─ DailyQuest (1)
            └─ DailyQuestTemplate (derived from)
```

User melihat quest di aplikasi mobile/web dan bisa:
- Update progress
- Complete quest
- Get rewards (XP & coins)

---

## 🔄 Daily Cycle

### Timeline Harian

```
DAY 1: Monday
├─ 00:00 → Scheduler runs `quests:generate`
│  └─ Template untuk Senin di-convert jadi DailyQuest
│  └─ DailyQuest di-assign ke 1000+ users
│
├─ 08:00 → Users start seeing quest
│
├─ Throughout day → Users complete quest, update progress
│
└─ 23:59 → Last chance untuk complete

DAY 2: Tuesday
└─ 00:00 → New quest dari Tuesday template di-generate
   └─ Previous day's quest no longer appears
```

---

## 📋 Database Tables Involved

### Table 1: `daily_quest_templates`
```sql
┌─────────────────────────────────────┐
│ daily_quest_templates               │
├─────────────────────────────────────┤
│ id (PK)                             │
│ day_of_week (0-6) [UNIQUE]          │
│ title                               │
│ description                         │
│ type (ENUM)                         │
│ target                              │
│ reward_exp                          │
│ reward_coins                        │
│ created_at                          │
│ updated_at                          │
└─────────────────────────────────────┘

7 records (one per day of week)
```

### Table 2: `daily_quests`
```sql
┌─────────────────────────────────────┐
│ daily_quests                        │
├─────────────────────────────────────┤
│ id (PK)                             │
│ title (copied from template)        │
│ description (copied from template)  │
│ type (copied from template)         │
│ target (copied from template)       │
│ reward_exp (copied from template)   │
│ reward_coins (copied from template) │
│ date [UNIQUE per day]               │
│ created_at                          │
│ updated_at                          │
└─────────────────────────────────────┘

~365 records per year (one per day)
```

### Table 3: `user_quests`
```sql
┌──────────────────────────────────────┐
│ user_quests                          │
├──────────────────────────────────────┤
│ id (PK)                              │
│ user_id (FK → users)                 │
│ daily_quest_id (FK → daily_quests)   │
│ progress (current progress)          │
│ completed (boolean)                  │
│ date                                 │
│ created_at                           │
│ updated_at                           │
└──────────────────────────────────────┘

~365,000 records per year
(1000 users × 365 days)
```

---

## 🔧 Implementation Methods

### Method 1: Manual Generation

```bash
# Generate quest untuk hari ini
php artisan quests:generate

# Generate quest untuk tanggal tertentu
php artisan quests:generate --date=2026-07-15

# Generate quest untuk seminggu ke depan (in code)
for ($i = 0; $i < 7; $i++) {
    $date = Carbon::now()->addDays($i);
    Artisan::call('quests:generate', ['--date' => $date->format('Y-m-d')]);
}
```

### Method 2: Automatic Scheduler (RECOMMENDED)

**Setup**:

1. **Edit `app/Console/Kernel.php`**:
```php
protected function schedule(Schedule $schedule): void
{
    // Jalankan setiap hari jam 00:00
    $schedule->command('quests:generate')
             ->dailyAt('00:00')
             ->withoutOverlapping()
             ->onSuccess(function () {
                 Log::info('Daily quests generated successfully');
             })
             ->onFailure(function () {
                 Log::error('Failed to generate daily quests');
             });
}
```

2. **Setup Cron Job di Server**:
```bash
# Edit crontab
crontab -e

# Add this line (runs scheduler every minute)
* * * * * cd /path/to/yokode && php artisan schedule:run >> /dev/null 2>&1
```

3. **Or Use Task Scheduler** (Laravel Forge, Vapor, etc):
- No manual cron needed
- Integrated scheduler management

### Method 3: Job/Queue

```php
// In any controller
use Illuminate\Support\Facades\Queue;
use App\Jobs\GenerateDailyQuests;

// Queue the job
Queue::dispatch(new GenerateDailyQuests());

// Or dispatch immediately
GenerateDailyQuests::dispatch();
```

---

## 🧪 Testing the Flow

### Step 1: Verify Templates Exist
```bash
php artisan tinker

>>> App\Models\DailyQuestTemplate::count()
7 // Should be 7 (one per day)

>>> App\Models\DailyQuestTemplate::all();
```

### Step 2: Generate Quest Manually
```bash
php artisan quests:generate

# Output:
# ✓ Daily quest created successfully!
#   Template: Login Challenge
#   Type: login
#   Target: 1
#   Rewards: 50 XP + 25 🪙
#   Assigned to: 1245 users
```

### Step 3: Verify Quest Created
```bash
php artisan tinker

>>> $quest = App\Models\DailyQuest::latest()->first();
>>> $quest->date->format('Y-m-d')
"2026-07-13"

>>> $quest->userQuests()->count()
1245 // Users who received this quest
```

### Step 4: Check User Progress
```php
>>> $user = App\Models\User::first();
>>> $user->userQuests()->latest()->first();
// Shows today's quest assignment
```

---

## 📈 Data Flow Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                        ADMIN SETUP (Once)                       │
│                                                                 │
│  Admin Panel → Setup 7 Templates (1 per day of week)            │
│     ↓                                                           │
│  daily_quest_templates table: 7 records                        │
│     ↓                                                           │
│  Template stored: title, desc, type, target, rewards           │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                    SCHEDULER/COMMAND (Daily)                    │
│                                                                 │
│  00:00 → Kernel scheduler triggers                             │
│     ↓                                                           │
│  php artisan quests:generate                                   │
│     ↓                                                           │
│  GenerateDailyQuests command executes                          │
│     ↓                                                           │
│  1. Get today's template from daily_quest_templates            │
│  2. Create new DailyQuest record (copy template data)          │
│  3. Assign to all users (create UserQuest records)             │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                    USER RECEIVES QUEST                          │
│                                                                 │
│  User sees quest in app                                        │
│     ↓                                                           │
│  User completes quest                                          │
│     ↓                                                           │
│  Progress updated in user_quests table                         │
│     ↓                                                           │
│  When completed: reward XP & coins to user                     │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                    NEXT DAY (Repeat)                            │
│                                                                 │
│  00:00 → New template becomes quest                            │
│  Previous day's quest becomes unavailable                      │
│  Cycle repeats...                                              │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🛠️ Troubleshooting

### Quest not generating?

1. **Check if templates exist**:
```bash
php artisan tinker
>>> App\Models\DailyQuestTemplate::count()
```

2. **Run command manually**:
```bash
php artisan quests:generate
# Check output for errors
```

3. **Check Laravel logs**:
```bash
tail -f storage/logs/laravel.log
```

### Scheduler not running?

1. **Verify cron is setup**:
```bash
crontab -l
# Should show: * * * * * cd /path/to/yokode && php artisan schedule:run >> /dev/null 2>&1
```

2. **Test scheduler manually**:
```bash
php artisan schedule:run
```

3. **Check server time**:
```bash
date
# Make sure server time is correct
```

### Users not receiving quests?

1. **Check if users are active**:
```bash
php artisan tinker
>>> App\Models\User::where('role', 'user')->count()
>>> App\Models\User::where('role', 'user')->where('is_active', true)->count()
```

2. **Verify quest assignment**:
```bash
>>> $quest = App\Models\DailyQuest::latest()->first();
>>> $quest->userQuests()->count()
```

---

## 💡 Best Practices

### 1. Timezone Consideration
```php
// In config/app.php
'timezone' => 'Asia/Jakarta', // Set appropriate timezone

// Then scheduler uses this timezone
$schedule->command('quests:generate')->dailyAt('00:00'); // 00:00 Jakarta time
```

### 2. Monitoring & Logging
```php
// In Kernel.php
$schedule->command('quests:generate')
         ->dailyAt('00:00')
         ->withoutOverlapping()
         ->onSuccess(function () {
             Log::channel('quests')->info('Daily quests generated at ' . now());
             // Send notification to admin
         })
         ->onFailure(function () {
             Log::channel('quests')->error('Failed to generate daily quests');
             // Send alert to admin
         });
```

### 3. Pre-generate Templates
```php
// Generate quests 7 days in advance
for ($i = 0; $i < 7; $i++) {
    $date = now()->addDays($i);
    if (!DailyQuest::where('date', $date->toDateString())->exists()) {
        Artisan::call('quests:generate', ['--date' => $date->format('Y-m-d')]);
    }
}
```

### 4. Performance Optimization
- Use `withoutOverlapping()` to prevent duplicate generation
- Consider using jobs/queues for large user bases (1000+)
- Add database indexes on `day_of_week` and `date`

---

## 📚 Related Files

- `app/Models/DailyQuestTemplate.php` - Template model
- `app/Models/DailyQuest.php` - Daily quest model
- `app/Models/UserQuest.php` - User quest tracking
- `app/Console/Commands/GenerateDailyQuests.php` - Generation command
- `app/Console/Kernel.php` - Scheduler configuration
- `app/Helpers/QuestHelper.php` - Helper methods

---

## ✅ Quick Checklist

- [ ] Templates initialized (7 records in daily_quest_templates)
- [ ] GenerateDailyQuests command created
- [ ] Kernel.php scheduler configured
- [ ] Cron job setup on server
- [ ] Test: `php artisan quests:generate` works
- [ ] Test: Quest appears in daily_quests table
- [ ] Test: UserQuest records created for all users
- [ ] Monitor logs for daily runs
- [ ] Setup alerts for failures

---

**Last Updated**: 2026-07-13
**Status**: Production Ready
