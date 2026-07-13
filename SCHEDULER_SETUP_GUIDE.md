# Scheduler Setup Guide - Daily Quest Auto-Generation

## 🎯 Tujuan
Membuat daily quest **otomatis** di-generate setiap hari jam 00:00 (tengah malam) dari template yang sudah di-setup admin.

---

## ✅ Prerequisites

1. Database sudah di-setup
2. Migration sudah dijalankan:
   ```bash
   php artisan migrate
   ```
3. Templates sudah di-inisialisasi (7 templates):
   ```bash
   php artisan db:seed --class=DailyQuestTemplateSeeder
   ```
4. `app/Console/Kernel.php` sudah ada (sudah dibuat)

---

## 🚀 Implementation Steps

### Step 1: Verify Kernel.php Exists

File: `app/Console/Kernel.php`

```php
<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Generate daily quests from templates every day at midnight
        $schedule->command('quests:generate')
                 ->dailyAt('00:00')
                 ->withoutOverlapping()
                 ->onSuccess(function () {
                     \Log::info('Daily quests generated successfully');
                 })
                 ->onFailure(function () {
                     \Log::error('Failed to generate daily quests');
                 });
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
```

**Status**: ✅ Already created in this project

---

### Step 2: Setup Cron Job on Server

The scheduler needs a cron job to run every minute. The scheduler will then determine which commands should run.

#### For Linux/Unix Servers

1. **SSH ke server**:
```bash
ssh user@your-server.com
```

2. **Edit crontab**:
```bash
crontab -e
```

3. **Add this line** (di akhir file):
```bash
* * * * * cd /path/to/yokode && php artisan schedule:run >> /dev/null 2>&1
```

**Explanation**:
- `* * * * *` = Run every minute
- `cd /path/to/yokode` = Go to project directory
- `php artisan schedule:run` = Check and run scheduled tasks
- `>> /dev/null 2>&1` = Redirect output (optional, for logging use `>> storage/logs/cron.log`)

4. **Verify cron is saved**:
```bash
crontab -l
# Should show the line you just added
```

#### For Windows Servers

1. **Open Task Scheduler**:
   - Press `Win + R`
   - Type `taskschd.msc`
   - Click OK

2. **Create Basic Task**:
   - Right-click → Create Basic Task
   - Name: "Laravel Scheduler"
   - Trigger: "Daily" at desired time (or "Repeat task" every 1 minute)

3. **Action**:
   - Program/script: `C:\path\to\php.exe`
   - Add arguments: `-r "cd 'C:\path\to\yokode' && artisan schedule:run"`

#### For Laravel Forge / Vapor (Recommended)

1. **Login to Forge Dashboard**
2. **Go to Scheduled Jobs**
3. **Create Job**:
   - Command: `php artisan quests:generate`
   - Frequency: Daily at 00:00 (or your preferred time)
4. **Save**

---

### Step 3: Configure Timezone (Optional but Important)

Edit `config/app.php`:

```php
'timezone' => 'Asia/Jakarta', // Change to your timezone
// Options: UTC, Asia/Jakarta, Asia/Bangkok, America/New_York, Europe/London, etc.
```

This ensures `dailyAt('00:00')` runs at the correct time for your location.

---

### Step 4: Test Scheduler Locally

#### Option A: Manual Test

```bash
# Generate quest for today
php artisan quests:generate

# Output should show:
# ✓ Daily quest created successfully!
#   Template: [Quest name]
#   Assigned to: [number] users
```

#### Option B: Simulate Scheduler

```bash
# This runs all scheduled tasks (regardless of time)
php artisan schedule:run

# Or test a specific command
php artisan quests:generate
```

#### Option C: Run in Foreground (For Testing)

```bash
# Keep watching for scheduled tasks
php artisan schedule:work

# This will show:
# - Tasks that are scheduled to run
# - When they run
# - Output from each task
# Press Ctrl+C to stop
```

---

### Step 5: Verify Setup is Working

#### Check Logs

```bash
# Watch real-time logs
tail -f storage/logs/laravel.log

# Should see entries like:
# [2026-07-13 00:00:01] local.INFO: Daily quests generated successfully
```

#### Query Database

```bash
php artisan tinker

>>> App\Models\DailyQuest::latest()->first()
// Should show today's quest with today's date

>>> App\Models\DailyQuest::all()->count()
// Should increase by 1 each day
```

#### Monitor User Assignments

```php
php artisan tinker

>>> $user = App\Models\User::first();
>>> $user->userQuests()->latest()->first()->daily_quest;
// Should show today's quest
```

---

## 🔧 Troubleshooting

### Issue 1: Quest not generating

**Solution A**: Check if GenerateDailyQuests command exists
```bash
php artisan list quests

# Should show: quests:generate
```

**Solution B**: Manually test the command
```bash
php artisan quests:generate

# If it works, scheduler setup might have issue
# If it fails, command needs debugging
```

**Solution C**: Check error logs
```bash
tail -f storage/logs/laravel.log
# Look for error messages
```

### Issue 2: Cron not running

**Check 1**: Verify cron job exists
```bash
crontab -l
# Should show the schedule:run line
```

**Check 2**: Verify PHP path
```bash
which php
# Use this path in crontab
```

**Check 3**: Test cron manually
```bash
# This is what cron runs
cd /path/to/yokode && php artisan schedule:run

# If this works, cron should work too
```

### Issue 3: Wrong timezone

**Symptom**: Quest generates at wrong time

**Solution**:
```php
// In config/app.php
'timezone' => 'Asia/Jakarta', // Your timezone

// Then restart application
php artisan cache:clear
php artisan config:clear
```

### Issue 4: Duplicate quests

**Solution**: Command already has `withoutOverlapping()`
```php
$schedule->command('quests:generate')
         ->dailyAt('00:00')
         ->withoutOverlapping(); // Prevents duplicates
```

If duplicates still appear, check:
```bash
php artisan schedule:clear-cache

# Then test again
```

---

## 📊 Monitoring & Alerts

### Setup Logging

Create `config/logging.php` entry:

```php
'channels' => [
    // ... other channels ...
    
    'quests' => [
        'driver' => 'single',
        'path' => storage_path('logs/quests.log'),
        'level' => 'debug',
    ],
],
```

Use in Kernel:

```php
$schedule->command('quests:generate')
         ->dailyAt('00:00')
         ->withoutOverlapping()
         ->onSuccess(function () {
             Log::channel('quests')->info('✓ Daily quests generated at ' . now());
         })
         ->onFailure(function () {
             Log::channel('quests')->error('✗ Failed to generate daily quests at ' . now());
             // Optional: Send notification
         });
```

### Setup Email Notifications (Advanced)

```php
use Illuminate\Support\Facades\Mail;

$schedule->command('quests:generate')
         ->dailyAt('00:00')
         ->withoutOverlapping()
         ->onFailure(function () {
             // Send email to admin
             Mail::send('emails.quest-generation-failed', [], function ($m) {
                 $m->to('admin@yokode.com');
             });
         });
```

---

## 🎯 Complete Setup Checklist

- [ ] **Kernel.php exists** with scheduler configuration
- [ ] **Timezone configured** correctly in `config/app.php`
- [ ] **Cron job setup** (or using Forge/Vapor)
- [ ] **Test command works**: `php artisan quests:generate`
- [ ] **Database has templates**: 7 records in `daily_quest_templates`
- [ ] **Logs configured** to track execution
- [ ] **Monitor running** (check logs daily for first week)
- [ ] **Alerts setup** for failures (email/slack)

---

## 📋 Verifying Daily Runs

### Daily Check (First Week)

```bash
# Each morning, run this to verify yesterday's quest was created
php artisan tinker

>>> App\Models\DailyQuest::where('date', now()->subDay())->first()
// Should show yesterday's quest

>>> App\Models\UserQuest::where('created_at', '>=', now()->subDay())->count()
// Should show thousands of entries (all users assigned yesterday's quest)
```

### Weekly Report

```bash
# Check quest generation for past 7 days
php artisan tinker

>>> App\Models\DailyQuest::where('date', '>=', now()->subDays(7))->count()
// Should be 7 (one per day)

>>> App\Models\DailyQuest::orderBy('date', 'desc')->limit(7)->get()->pluck('title', 'date')
// Shows all 7 quests generated this week
```

---

## 🚀 Performance Tips

1. **Use `withoutOverlapping()`** - Prevent duplicate runs
2. **Set timezone** - Run at correct time
3. **Monitor logs** - Catch issues early
4. **Backup templates** - Don't delete templates accidentally
5. **Test before deploying** - Verify locally first

---

## 🔗 Related Documentation

- `TEMPLATE_TO_DAILY_QUEST_FLOW.md` - Complete flow explanation
- `app/Console/Commands/GenerateDailyQuests.php` - Command implementation
- `app/Console/Kernel.php` - Scheduler configuration
- Laravel Scheduler docs: https://laravel.com/docs/scheduling

---

## 📞 Support

If scheduler is not working:

1. Check `storage/logs/laravel.log` for errors
2. Run `php artisan quests:generate` manually to test
3. Verify cron job: `crontab -l`
4. Verify timezone: `php artisan config:get app.timezone`
5. Check PHP version: `php --version`

---

**Last Updated**: 2026-07-13
**Status**: ✅ Production Ready

