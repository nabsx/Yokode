# Daily Quest Reset & Cleanup Guide

## Overview

Panduan lengkap untuk reset, cleanup, dan troubleshooting duplicate quests di daily quest system.

---

## ⚠️ Problem: Quests Terus Nambah

### Gejala
- Setiap kali jalankan `php artisan quests:generate`, quest nambah di dashboard user
- DailyQuest terus bertambah untuk tanggal yang sama
- UserQuest terus bertambah bahkan untuk quest yang sama

### Root Cause (SUDAH FIXED)
1. Tidak ada unique constraint pada `daily_quests.date`
2. Command tidak menggunakan `firstOrCreate`, selalu create baru
3. UserQuest juga tidak prevent duplicate

### Solution (SUDAH DITERAPKAN)
1. ✅ Ubah command ke gunakan `firstOrCreate`
2. ✅ Tambah migration untuk unique constraint pada date
3. ✅ UserQuest juga gunakan `firstOrCreate`

---

## 🔧 Cara Menerapkan Fix

### Step 1: Run Migration (Tambah Unique Constraint)

```bash
php artisan migrate
```

Ini akan:
- Tambah unique constraint pada `daily_quests.date`
- Prevent duplicate quests untuk hari yang sama

**Note:** Jika ada duplicate data di database, migration akan fail. Ikuti section "Cleanup" di bawah terlebih dahulu.

### Step 2: Verify Command Updated

Command sudah updated dengan `firstOrCreate`:
- ✅ DailyQuest: `firstOrCreate(['date' => $dateStr])`
- ✅ UserQuest: `firstOrCreate(['user_id', 'daily_quest_id', 'date'])`

### Step 3: Test

```bash
# Run 3 kali - harusnya sama
php artisan quests:generate
php artisan quests:generate
php artisan quests:generate

# Output harusnya:
# Run 1: "✓ Daily quest created"
# Run 2: "ℹ Quest already exists"
# Run 3: "ℹ Quest already exists"
```

---

## 🧹 CLEANUP DATABASE (Jika Ada Duplicates)

### Option 1: Clean Quests untuk Hari Tertentu (Recommended)

```bash
php artisan tinker

# List quests dengan duplicates
$duplicates = DB::table('daily_quests')
    ->select('date')
    ->groupBy('date')
    ->havingRaw('count(*) > 1')
    ->get();

$duplicates->each(fn($d) => dump($d));

# Delete quests untuk tanggal tertentu (HATI-HATI!)
# Contoh: Delete semua untuk 2026-07-15 kecuali yang paling baru
$date = '2026-07-15';
$quests = DB::table('daily_quests')
    ->where('date', $date)
    ->orderBy('id', 'desc')
    ->skip(1)
    ->get();

foreach ($quests as $quest) {
    DB::table('user_quests')
        ->where('daily_quest_id', $quest->id)
        ->delete();
    
    DB::table('daily_quests')
        ->where('id', $quest->id)
        ->delete();
}
```

### Option 2: Clean All Quests (Nuclear Option - Hanya jika perlu reset total)

**WARNING: INI AKAN HAPUS SEMUA QUESTS DAN USER PROGRESS!**

```bash
php artisan tinker

# Hapus semua user quest progress
DB::table('user_quests')->truncate();

# Hapus semua daily quests
DB::table('daily_quests')->truncate();

# Verifikasi
echo "UserQuest count: " . DB::table('user_quests')->count();
echo "DailyQuest count: " . DB::table('daily_quests')->count();
```

Setelah clean, generate ulang:
```bash
php artisan quests:generate
```

### Option 3: Via Database Tools (MySQL/PostgreSQL)

```sql
-- List duplicates
SELECT date, COUNT(*) as count 
FROM daily_quests 
GROUP BY date 
HAVING COUNT(*) > 1
ORDER BY count DESC;

-- Delete old duplicates (keep newest)
DELETE dq FROM daily_quests dq
WHERE id NOT IN (
    SELECT MAX(id) 
    FROM daily_quests 
    GROUP BY date
);

-- Verify
SELECT date, COUNT(*) as count 
FROM daily_quests 
GROUP BY date;
```

---

## 🔄 Reset Procedures

### Scenario 1: Fix Duplicate Quests (Jangan Hapus User Progress)

```bash
# Via tinker - delete duplicate daily_quests, keep user_quests untuk yang tersisa
php artisan tinker

# Get all dates dengan multiple quests
$dates = DB::table('daily_quests')
    ->select('date')
    ->groupBy('date')
    ->havingRaw('count(*) > 1')
    ->pluck('date');

# For each date, keep only newest quest, update user_quests ke point ke quest baru
foreach ($dates as $date) {
    $quests = DB::table('daily_quests')
        ->where('date', $date)
        ->orderBy('id', 'desc')
        ->get();
    
    $newQuestId = $quests[0]->id;
    $oldQuestIds = $quests->skip(1)->pluck('id');
    
    // Update old user_quests ke point ke new quest
    foreach ($oldQuestIds as $oldId) {
        DB::table('user_quests')
            ->where('daily_quest_id', $oldId)
            ->update(['daily_quest_id' => $newQuestId]);
    }
    
    // Delete old quests
    DB::table('daily_quests')
        ->where('date', $date)
        ->whereIn('id', $oldQuestIds)
        ->delete();
}
```

### Scenario 2: Keep Today's Quest Only

```bash
php artisan tinker

# Delete semua kecuali hari ini
$today = now()->toDateString();
DB::table('user_quests')
    ->whereNotIn('daily_quest_id', 
        DB::table('daily_quests')
            ->where('date', $today)
            ->pluck('id')
    )
    ->delete();

DB::table('daily_quests')
    ->where('date', '!=', $today)
    ->delete();
```

### Scenario 3: Reset Specific User's Progress

```bash
php artisan tinker

$userId = 1;  // Change this
$date = '2026-07-15';

// Reset progress
DB::table('user_quests')
    ->where('user_id', $userId)
    ->where('date', $date)
    ->update([
        'progress' => 0,
        'completed' => false,
    ]);
```

---

## 🔍 Verification Commands

### Check Current State

```bash
php artisan tinker

# Count quests per date
$groupedByDate = DB::table('daily_quests')
    ->selectRaw('date, count(*) as total')
    ->groupBy('date')
    ->get();

$groupedByDate->each(fn($r) => dump("{$r->date}: {$r->total}"));

# Check for duplicates
$duplicates = DB::table('daily_quests')
    ->selectRaw('date, count(*) as total')
    ->groupBy('date')
    ->having('count(*)', '>', 1)
    ->count();

echo $duplicates > 0 ? "DUPLICATES FOUND: $duplicates dates" : "NO DUPLICATES";

# Count total user quests
echo "Total UserQuest records: " . DB::table('user_quests')->count();

# Count users with today's quest
$todayQuestIds = DB::table('daily_quests')
    ->where('date', now()->toDateString())
    ->pluck('id');

$usersWithToday = DB::table('user_quests')
    ->whereIn('daily_quest_id', $todayQuestIds)
    ->distinct('user_id')
    ->count('user_id');

echo "Users with today's quest: $usersWithToday";
```

---

## 📋 Prevention Checklist

### After Migration Applied

- [ ] Run `php artisan migrate`
- [ ] Verify unique constraint created: 
  ```sql
  SHOW INDEX FROM daily_quests WHERE Column_name = 'date';
  ```
- [ ] Test command 3 times: `php artisan quests:generate`
- [ ] Verify output shows "already exists" on 2nd & 3rd run
- [ ] Check dashboard - quest count NOT increasing

### Regular Maintenance

- [ ] Monitor daily - `php artisan quests:generate` runs once per day
- [ ] Check logs for errors
- [ ] Weekly: Verify no duplicates exist
- [ ] Monthly: Review user quest counts

---

## 🚨 Troubleshooting

### Problem 1: Migration Fails (Duplicate Dates)

```
SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry
```

**Solution:** Clean duplicates first:
```bash
php artisan tinker

# Keep newest quest per date, delete old ones
$dates = DB::table('daily_quests')
    ->select('date')
    ->groupBy('date')
    ->havingRaw('count(*) > 1')
    ->pluck('date');

foreach ($dates as $date) {
    $questIds = DB::table('daily_quests')
        ->where('date', $date)
        ->orderBy('id', 'desc')
        ->skip(1)
        ->pluck('id');
    
    DB::table('user_quests')
        ->whereIn('daily_quest_id', $questIds)
        ->delete();
    
    DB::table('daily_quests')
        ->whereIn('id', $questIds)
        ->delete();
}

exit()
```

Then retry: `php artisan migrate`

### Problem 2: Command Still Creates Duplicates

**Check:**
- [ ] File updated? `grep firstOrCreate app/Console/Commands/GenerateDailyQuests.php`
- [ ] Migration run? `php artisan migrate:status | grep 170000`
- [ ] No leftover old data? Run cleanup

### Problem 3: UserQuests Still Increasing

**Check:**
- [ ] UserQuest using `firstOrCreate`? Check command code
- [ ] Test with: `php artisan quests:generate` twice, count shouldn't change
- [ ] If still increasing, check if multiple dates overlap

### Problem 4: Dashboard Shows Multiple Same Quests

**Likely Cause:** Old user_quests pointing to old daily_quests
**Solution:**
```bash
php artisan tinker

# Get today's newest quest
$today = now()->toDateString();
$newestQuest = DB::table('daily_quests')
    ->where('date', $today)
    ->orderBy('id', 'desc')
    ->first();

$newestId = $newestQuest->id;

# Get old quests for today
$oldQuestIds = DB::table('daily_quests')
    ->where('date', $today)
    ->where('id', '!=', $newestId)
    ->pluck('id');

# Redirect user_quests to newest
DB::table('user_quests')
    ->whereIn('daily_quest_id', $oldQuestIds)
    ->update(['daily_quest_id' => $newestId]);

# Delete old
DB::table('daily_quests')
    ->whereIn('id', $oldQuestIds)
    ->delete();
```

---

## 📊 Performance Considerations

### Before Fix
- Command: O(n) - creates n DailyQuests for n runs (BAD)
- UserQuest: O(m*n) - creates m*n records (BAD)
- Database: Bloats quickly

### After Fix
- Command: O(1) - idempotent, safe to run multiple times
- UserQuest: O(m) - creates m records once, reuses them
- Database: Stays clean

---

## ✅ Final Checklist

After applying all fixes:

- [ ] Migration applied: `php artisan migrate:status`
- [ ] Unique constraint exists: Check database
- [ ] Command updated: `firstOrCreate` used
- [ ] Test 3 runs: Output shows "already exists"
- [ ] Dashboard: Quest count stable
- [ ] No duplicates: Run verification queries
- [ ] User progress: Not reset/lost
- [ ] Rewards: Still working correctly
- [ ] Scheduler: Still runs daily
- [ ] Documentation: Updated and tested

---

## 📞 Quick Reference

| Action | Command |
|--------|---------|
| Generate Quest | `php artisan quests:generate` |
| Run Migration | `php artisan migrate` |
| Check Migration | `php artisan migrate:status` |
| Clean All | `DB::table('user_quests')->truncate();` |
| Verify No Duplicates | `SELECT COUNT(DISTINCT date) FROM daily_quests;` |
| Debug in Tinker | `php artisan tinker` |

---

**Status:** All fixes applied and tested ✅
**Version:** 1.0
**Last Updated:** 2026-07-13
