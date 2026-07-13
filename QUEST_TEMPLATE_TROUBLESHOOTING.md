# 🔧 Quest Template - Troubleshooting Guide

## Problem 1: Quest tidak muncul di dashboard user

### Symptoms:
- User login tapi tidak lihat daily quests
- Dashboard menampilkan "Belum ada misi hari ini"

### Solutions:

**1. Check apakah daily_quest_templates table ada dan ter-isi:**
```bash
# Via command line
php artisan tinker
> App\Models\DailyQuestTemplate::count()
# Should return: 7
```

**2. Jika templates kosong, initialize sekarang:**
```bash
# Via seeder
php artisan db:seed --class=DailyQuestTemplateSeeder

# ATAU via Admin Panel
# Buka: /admin/quest-templates
# Klik: "Initialize Default Templates"
```

**3. Generate daily quest untuk hari ini:**
```bash
php artisan quests:generate

# Check output untuk memastikan berhasil:
# ✓ Daily quest created successfully!
```

**4. Verify user quests ter-create:**
```bash
php artisan tinker
> App\Models\UserQuest::where('user_id', 1)->where('date', now()->toDateString())->count()
# Should return: > 0
```

---

## Problem 2: Tampilan quest tidak berubah saat di-refresh

### Symptoms:
- Edit template di admin panel
- Kembali ke user dashboard
- Tampilan quest masih lama

### Solutions:

**1. Clear cache browser:**
- Tekan `Ctrl+Shift+R` (hard refresh)
- Atau buka Developer Tools → Network → Disable cache

**2. Clear Laravel cache:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

**3. Untuk user yang sudah ada UserQuest, recreate:**
```bash
# Delete existing user quests for today
php artisan tinker
> App\Models\UserQuest::where('user_id', 1)->where('date', now()->toDateString())->delete()

# Logout user dan login lagi
# System akan auto-create new quests
```

---

## Problem 3: Hadiah/Reward tidak diberikan saat complete quest

### Symptoms:
- User complete quest (progress 100%)
- Tapi coins/XP tidak bertambah
- Status quest tidak berubah jadi completed

### Solutions:

**1. Check apakah reward_coins dan reward_exp ter-set di template:**
```bash
php artisan tinker
> $template = App\Models\DailyQuestTemplate::where('day_of_week', 1)->first()
> $template->reward_exp
> $template->reward_coins
# Both should > 0
```

**2. Check logika updateQuestProgress di User model:**
- Verify method `updateQuestProgress()` terpanggil saat:
  - Lesson selesai → `updateQuestProgress('complete_lesson')`
  - Quiz dijawab → `updateQuestProgress('answer_quiz')`
  - EXP didapat → method `addExp()` auto-call ini

**3. Manual test update progress:**
```bash
php artisan tinker
> $user = App\Models\User::find(1)
> $user->updateQuestProgress('complete_lesson', 1)
# Check if coins increased:
> $user->fresh()->coins
```

**4. Check UserQuest model - pastikan ada relasi ke DailyQuest:**
```bash
php artisan tinker
> $userQuest = App\Models\UserQuest::first()
> $userQuest->dailyQuest
# Should show the quest data
```

**5. Verify progress tracking logic:**
```bash
php artisan tinker
# Check specific user quest
> $uq = App\Models\UserQuest::find(1)
> $uq->progress
> $uq->completed
> $uq->dailyQuest->target
```

---

## Problem 4: Template changes tidak muncul untuk new users

### Symptoms:
- Admin ubah template (misal target dari 1 → 5)
- New user login dan dapat quest dengan target lama

### Solutions:

**1. Verify template ter-update:**
```bash
php artisan tinker
> $template = App\Models\DailyQuestTemplate::find(1)
> $template->target
# Should be the new value
```

**2. Check when was quest created in daily_quests:**
```bash
php artisan tinker
> $quest = App\Models\DailyQuest::latest()->first()
> $quest->date
> $quest->target
# If date is today and target is old, need to regenerate
```

**3. Delete daily quests for today dan regenerate:**
```bash
php artisan tinker
> App\Models\DailyQuest::where('date', now()->toDateString())->delete()
# Then run:
php artisan quests:generate
# New users akan dapat updated template
```

---

## Problem 5: Admin edit template tapi ga ada effect

### Symptoms:
- Edit quest template title/description/rewards
- Save successfully
- Tapi di user dashboard tetap tampil yang lama

### Solutions:

**1. Verify edit form submit properly:**
- Check Network tab di browser DevTools
- Pastikan PUT request sent dengan proper data

**2. Check if form menggunakan @csrf token:**
```blade
<form method="POST" action="{{ route('admin.quest-templates.update', $template) }}">
    @csrf
    @method('PUT')
    <!-- fields -->
</form>
```

**3. Verify route & controller method:**
```bash
grep -n "quest-templates.update" /vercel/share/v0-project/routes/web.php
grep -n "questTemplatesUpdate" /vercel/share/v0-project/app/Http/Controllers/AdminController.php
```

**4. Check Laravel logs:**
```bash
tail -f storage/logs/laravel.log
# Look for any errors during save
```

**5. Direct database check:**
```bash
php artisan tinker
> $template = App\Models\DailyQuestTemplate::find(1)
> $template->title
# Should be the updated value
```

---

## Problem 6: Scheduler tidak berjalan (Quests tidak auto-generate)

### Symptoms:
- Setup scheduler sudah benar
- Tapi quests tidak auto-generate jam 00:00
- User harus manual run `php artisan quests:generate`

### Solutions:

**1. Verify cron job ter-set:**
```bash
crontab -l
# Should show: * * * * * cd /path/to/yokode && php artisan schedule:run >> /dev/null 2>&1
```

**2. Verify Kernel.php ada schedule entry:**
```bash
grep -n "schedule->command" /vercel/share/v0-project/app/Console/Kernel.php
# Should show: quests:generate
```

**3. Manual test scheduler:**
```bash
# Force run scheduler
php artisan schedule:run

# Check if command executed
php artisan quests:generate
```

**4. Check laravel.log for scheduler runs:**
```bash
tail -100 storage/logs/laravel.log | grep "quests:generate"
```

**5. If using cloud hosting (Forge, Vapor, etc):**
- Check if server has cron enabled
- Contact hosting provider to verify cron service

---

## Problem 7: Multiple quests created for same day

### Symptoms:
- Daily quest muncul duplicate
- User punya 2-3 quests dengan title sama

### Solutions:

**1. Check unique constraint di database:**
```bash
php artisan tinker
> App\Models\DailyQuest::where('date', now()->toDateString())->count()
# Should be 1 or very few (not many duplicates)
```

**2. Check GenerateDailyQuests command menggunakan transaction:**
```bash
# Verify di: app/Console/Commands/GenerateDailyQuests.php
# Should use: DB::beginTransaction() dan DB::commit()
```

**3. Check UserQuest using firstOrCreate (not create):**
```bash
# Verify di: app/Models/User.php createDailyQuests()
# Should use: UserQuest::firstOrCreate([...])
```

**4. If duplicates exist, clean up:**
```bash
php artisan tinker
> // Delete all quests for today
> App\Models\DailyQuest::where('date', now()->toDateString())->delete()
> // Regenerate
> \Illuminate\Support\Facades\Artisan::call('quests:generate')
```

---

## Problem 8: Wrong template untuk hari yang salah

### Symptoms:
- Hari ini Senin, tapi quest yang muncul adalah template Rabu
- Atau: wrong template untuk day of week

### Solutions:

**1. Check day_of_week di template:**
```bash
php artisan tinker
> App\Models\DailyQuestTemplate::all(['day_of_week', 'title'])
# 0=Sunday, 1=Monday, 2=Tuesday, ..., 6=Saturday
# Verify mapping correct
```

**2. Check current day:**
```bash
php artisan tinker
> now()->dayOfWeek
# Should match one of templates
```

**3. If wrong, fix dengan force date:**
```bash
php artisan quests:generate --date=2026-07-14
# Will use correct day_of_week from date
```

**4. Check database:**
```bash
# Verify each day has exactly 1 template
php artisan tinker
> App\Models\DailyQuestTemplate::groupBy('day_of_week')->get()->count()
# Should be 7
```

---

## Debugging Checklist

Jika ada masalah, jalankan checklist ini:

```bash
# 1. Check templates exist
php artisan tinker
> App\Models\DailyQuestTemplate::count()

# 2. Check today's daily quests
> App\Models\DailyQuest::where('date', now()->toDateString())->count()

# 3. Check user's quests for today
> App\Models\UserQuest::where('user_id', 1)->where('date', now()->toDateString())->get()

# 4. Check quest rewards
> $quest = App\Models\DailyQuest::latest()->first()
> $quest->reward_exp
> $quest->reward_coins

# 5. Check progress tracking works
> $user = App\Models\User::find(1)
> $user->total_exp
> $user->coins
> $user->updateQuestProgress('complete_lesson', 1)
> $user->fresh()->total_exp

# 6. Check scheduler (if configured)
php artisan schedule:run
tail -f storage/logs/laravel.log
```

---

## Quick Reset (Nuclear Option)

Jika semuanya berantakan, reset semua:

```bash
# 1. Delete all quests and user quests
php artisan tinker
> App\Models\UserQuest::truncate()
> App\Models\DailyQuest::truncate()

# 2. Re-initialize templates
php artisan db:seed --class=DailyQuestTemplateSeeder

# 3. Generate fresh quests for today
php artisan quests:generate

# 4. Clear cache
php artisan cache:clear

# 5. Have users logout and login again
# System akan auto-create fresh user quests
```

---

## Monitoring & Logging

Tambahkan logging untuk monitor quest system:

```php
// In app/Models/User.php createDailyQuests()
\Log::info("Creating daily quests for user {$this->id}");
\Log::info("Found templates count: " . $templates->count());
```

Check logs:
```bash
tail -f storage/logs/laravel.log
```

---

## Performance Tips

Jika punya banyak users (10,000+):

1. **Use chunk untuk assign quests:**
```php
User::where('is_active', true)->chunk(1000, function($users) {
    foreach ($users as $user) {
        $user->createDailyQuests();
    }
});
```

2. **Use queue untuk async:**
```php
dispatch(new GenerateQuestsJob($user));
```

3. **Optimize queries dengan select:**
```php
UserQuest::select('id', 'progress', 'completed', 'daily_quest_id')
    ->where('user_id', $this->id)
    ->where('date', $today)
    ->get();
```

---

## Contact Support

Jika masalah masih terjadi, cek:
- Laravel logs: `storage/logs/laravel.log`
- Database: `daily_quest_templates`, `daily_quests`, `user_quests`
- Browser DevTools Console & Network tab
- Server cron logs (if applicable)
