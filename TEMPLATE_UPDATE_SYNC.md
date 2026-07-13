# Template Update Sync Guide

## Masalah yang Dipecahkan

Sebelumnya, saat admin mengedit template rewards (XP & coins), user dashboard tidak langsung menampilkan perubahan. Ini karena:

1. Template tersimpan di `daily_quest_templates` table
2. Quests yang ditampilkan di user dashboard dari `daily_quests` table
3. Ketika template diedit, `daily_quests` lama tidak diupdate

## Solusi yang Diterapkan

### Fitur 1: Auto-Update Existing Quest (Hari Ini)

Saat admin mengedit template untuk hari ini, system otomatis update quest yang sudah dibuat:

```php
// Di AdminController::questTemplatesUpdate()
if ($template->day_of_week == Carbon::now()->dayOfWeek) {
    DailyQuest::where('date', $today)->update([
        'reward_exp' => $validated['reward_exp'],    // Update rewards
        'reward_coins' => $validated['reward_coins'],
        // ... dan field lainnya
    ]);
}
```

**Hasil:**
- Admin edit template Monday → Monday quest rewards diupdate
- User dashboard langsung menampilkan rewards baru
- No page refresh needed (except browser cache)

### Fitur 2: Future Quests Use New Template

Quests untuk hari-hari depan akan otomatis menggunakan template yang sudah diedit:

```
Monday (Today):
├─ Edit template: 5000 XP → Update existing quest ✓
└─ Dashboard shows: 5000 XP immediately ✓

Tuesday (Tomorrow):
├─ Command generate quest tomorrow
├─ Read updated template: 5000 XP
└─ Create quest dengan rewards baru ✓
```

## Use Case & Behavior

### Scenario 1: Edit Today's Template

**Admin Actions:**
1. Admin Panel → Quest Templates
2. Find "Monday" template
3. Change: reward_exp 100 → 5000
4. Change: reward_coins 50 → 2500
5. Click Save

**Result:**
- Template updated: `daily_quest_templates`
- Today's quest updated: `daily_quests` (auto)
- User dashboard updated (browser refresh atau wait cache)
- Message: "Daily quest template updated successfully! Dashboard will refresh to show new rewards."

### Scenario 2: Edit Tomorrow's Template (Future Day)

**Admin Actions:**
1. Admin Panel → Quest Templates
2. Find "Tuesday" template
3. Edit template (Tuesday belum mulai)
4. Click Save

**Result:**
- Template updated: `daily_quest_templates`
- Today's quest: Not affected (different day)
- Tomorrow's quest: When generated, use new template
- User: Will see new rewards starting tomorrow

### Scenario 3: Edit Past Template

**Admin Actions:**
1. Admin Panel → Quest Templates
2. Find "Sunday" template
3. Edit (today sudah Senin)
4. Click Save

**Result:**
- Template updated: `daily_quest_templates`
- Today's quest: Not affected (different day)
- Future Sundays: Use new template
- User: Old Sunday quest not affected, future Sundays have new rewards

## Database Flow

### Tables Involved

```
daily_quest_templates (Static - Admin Setup)
├─ day_of_week, title, description, type, target, reward_exp, reward_coins
└─ Updated when: Admin edits

daily_quests (Per-Day Instances)
├─ date, title, description, type, target, reward_exp, reward_coins
├─ Created: Daily via scheduler/command
└─ Updated: Auto-sync when template of same day updated

user_quests (User Progress)
├─ user_id, daily_quest_id, date, progress, completed
├─ Reads: From daily_quests
├─ Created: When user login
└─ Updated: When user completes action
```

### Update Flow

```
Admin Edit Template (Monday)
        ↓
questTemplatesUpdate() called
        ↓
Check: Is today Monday?
  ├─ YES: Auto-update daily_quests (date = today) ✓
  └─ NO: Skip update (will affect tomorrow's quest)
        ↓
Template saved ✓
User dashboard: Fetch today's quest from daily_quests ✓
Shows new rewards ✓
```

## API Integration

### Update Template & Sync Quest

Endpoint: `PUT /admin/quest-templates/{template}`

```php
// Request
{
    "title": "Lesson Completion",
    "description": "Complete 1 lesson",
    "type": "complete_lesson",
    "target": 1,
    "reward_exp": 5000,      // ← Changed from 100
    "reward_coins": 2500     // ← Changed from 50
}

// Response
{
    "success": true,
    "message": "Daily quest template updated successfully! Dashboard will refresh to show new rewards.",
    "template": {
        "day_of_week": 1,
        "title": "Lesson Completion",
        "reward_exp": 5000,
        "reward_coins": 2500,
        "auto_synced": true,
        "synced_for_date": "2026-07-14"
    }
}
```

## Testing

### Manual Test: Edit Today's Template

**Step 1: Check Current Rewards**
```
User Dashboard:
- Quest: "Lesson Completion"
- Current Reward: 100 XP, 50 coins
```

**Step 2: Edit Template (as Admin)**
```
Admin Panel → Quest Templates
Find: Monday (if today is Monday)
Change: reward_exp = 5000, reward_coins = 2500
Save
```

**Step 3: Refresh Dashboard**
```
User Dashboard:
- Same Quest: "Lesson Completion"
- New Reward: 5000 XP, 2500 coins ✓
```

### Test Case: Multiple Users

All users should see updated rewards:

```bash
# Test dengan tinker
> $quest = App\Models\DailyQuest::where('date', today())->first();
> $quest->reward_exp
=> 5000  ✓

> $users = App\Models\User::limit(5)->get();
> $users->each(fn($u) => print_r($u->todayQuest->reward_exp));
=> All show 5000  ✓
```

## Performance Considerations

### Query Efficiency

```php
// One query: Find & update
DailyQuest::where('date', $today)->update([...])
// ✓ Single DB hit
// ✓ Atomic operation
// ✓ No loops
```

### Caching Implications

If app uses caching:

```php
// Clear cache after update
Cache::forget("user:{$userId}:quests:today");
Cache::forget("daily_quest:today");
```

## Troubleshooting

### Issue: Dashboard Still Shows Old Rewards

**Cause 1: Browser Cache**
- Clear browser cache
- Hard refresh (Ctrl+Shift+R or Cmd+Shift+R)

**Cause 2: DB Not Updated**
```bash
# Check daily_quests entry
> DB::table('daily_quests')->where('date', today())->first();
# Verify reward_exp & reward_coins match template
```

**Cause 3: Wrong Day**
```bash
# If template edit is for future day (not today)
# Only today's template updates today's quest
# Tomorrow's quest updates tomorrow
```

### Issue: Update Didn't Apply

**Verify Today Match:**
```bash
# Check day_of_week
> now()->dayOfWeek  # Should match template.day_of_week
```

**Manual Update:**
```bash
php artisan tinker

$today = today()->toDateString();
DB::table('daily_quests')->where('date', $today)->update([
    'reward_exp' => 5000,
    'reward_coins' => 2500,
]);

exit
```

## Best Practices

### 1. Edit Templates Early in Day

Edit templates early in the day so user see changes immediately.

```
Good:  Edit at 08:00 AM → Users see changes all day
Bad:   Edit at 11:59 PM → Users see changes for 1 minute only
```

### 2. Plan Future Template Changes

For significant changes, edit tomorrow's or next week's template:

```
Today: Monday
├─ Today's quest: Already created
├─ Edit Monday template: Affects today ✓
└─ Edit Tuesday template: Affects tomorrow ✓
```

### 3. Verify Changes Before Going Live

Test with tinker before major updates:

```bash
php artisan tinker

# Verify template
$t = App\Models\DailyQuestTemplate::find(1);
$t->reward_exp  # Check value

# Check current daily_quests
$q = App\Models\DailyQuest::where('date', today())->first();
$q->reward_exp  # Before/after

exit
```

### 4. Document Changes

For admin reference:

```
Log: Template Updates
├─ 2026-07-14 10:30 - Monday rewards updated: 100→5000 XP, 50→2500 coins
├─ Reason: Engagement boost for summer campaign
└─ Users affected: All active users on 2026-07-14
```

## Summary

- **Admin edits today's template** → Today's quest rewards auto-updated ✓
- **Admin edits future template** → Future quest uses new template ✓
- **User sees updated rewards** → After browser refresh (or cache clear) ✓
- **Performance** → Single query update, very fast ✓
- **Data consistency** → No duplicates, clean data ✓

## Production Checklist

- [x] Code deployed
- [ ] Test with multiple users
- [ ] Verify dashboard updates
- [ ] Check logs for errors
- [ ] Monitor performance
- [ ] Document any issues
- [ ] Train admins on timing
- [ ] Ready for production ✓
