# Daily Quest Templates Implementation Guide

## Overview
This feature allows admins to create and manage daily quest templates for each day of the week (Monday through Sunday). Each day can have a unique quest that users will receive automatically.

## What's New

### Database
- **New Migration**: `2026_07_13_160000_add_day_of_week_to_daily_quests_table.php`
  - Adds `day_of_week` column (tinyInteger, nullable)
  - Values: 0=Monday, 1=Tuesday, 2=Wednesday, 3=Thursday, 4=Friday, 5=Saturday, 6=Sunday

### Model Updates
- **DailyQuest Model** (`app/Models/DailyQuest.php`)
  - Added `day_of_week` to `$fillable` array
  - New methods:
    - `getByDayOfWeek($dayOfWeek)` - Get template for specific day
    - `getWeeklyTemplates()` - Get all 7 day templates organized
    - `getDayName()` - Get human-readable day name

### Routes Added
```php
// View all templates
GET  /admin/daily-quests/templates
Name: admin.quests.templates

// Edit template for specific day
GET  /admin/daily-quests/templates/{dayOfWeek}/edit
Name: admin.quests.template.edit

// Update template for specific day
PUT  /admin/daily-quests/templates/{dayOfWeek}
Name: admin.quests.template.update
```

### Controller Methods
Location: `app/Http/Controllers/AdminController.php`

#### `dailyQuestTemplates()`
- Retrieves all 7 day templates
- Returns view with template overview

#### `dailyQuestTemplateEdit($dayOfWeek)`
- Validates day of week (0-6)
- Fetches existing quest if available
- Returns edit form

#### `dailyQuestTemplateUpdate(Request $request, $dayOfWeek)`
- Validates form data:
  - `title` (required, string, max 255)
  - `description` (required, string, max 500)
  - `type` (required, one of: complete_lesson, answer_quiz, gain_exp, login, perfect_quiz)
  - `target` (required, integer, min 1)
  - `reward_exp` (required, integer, min 0)
  - `reward_coins` (optional, integer, min 0)
- Creates new template if doesn't exist
- Updates existing template if exists

### Views Created

#### `resources/views/admin/quests/templates.blade.php`
- Grid view showing all 7 days
- Each day shows:
  - Day name (highlighted header)
  - Quest title
  - Quest description (truncated)
  - Quest type badge
  - Target number
  - Reward amounts (XP and coins)
  - Edit button
- Empty state if no quest for a day
- Info box explaining how templates work

#### `resources/views/admin/quests/template-edit.blade.php`
- Form to create/edit quest template for a specific day
- Fields:
  - Quest Title
  - Quest Type (dropdown)
  - Description (textarea)
  - Target Number (number input)
  - Reward XP (number input)
  - Reward Coins (number input, optional)
- **Live Preview Card** showing how the quest will look
- JavaScript that updates preview in real-time as user types
- Save and Cancel buttons

### Admin Panel Updates
- **Sidebar Navigation** (`resources/views/layouts/admin.blade.php`)
  - New menu item: "Quest Templates"
  - Icon: calendar-days
  - Position: Below "Daily Quests"
  - Automatically highlighted when viewing templates

## How It Works

### Admin Workflow
1. Navigate to Admin Dashboard → Gamification → Quest Templates
2. View 7-day grid showing current template for each day
3. Click "Edit" on any day to open the edit form
4. Enter quest details:
   - Title (e.g., "Complete 3 Lessons")
   - Type (from dropdown)
   - Description (explanation of the quest)
   - Target (number to achieve)
   - Rewards (XP and optional coins)
5. Watch the live preview update as you type
6. Click "Save Template" to save

### User Side (Future Implementation)
- System will check the current day
- Fetch the template for that day
- Create daily quest for user
- User completes the quest to get rewards

## Features

✅ **Template Management**
- Create templates for each day of the week
- Edit existing templates
- Different quest for each day

✅ **Validation**
- Type validation (only valid quest types)
- Required field validation
- Number range validation

✅ **User Experience**
- Live preview of how quest will look
- Grid view for easy day-at-a-glance management
- Clear visual hierarchy
- Helpful descriptions

✅ **Scalability**
- Easy to create new quests
- Easy to modify existing ones
- Backward compatible with existing date-based quests

## Database Schema

```sql
ALTER TABLE daily_quests ADD COLUMN day_of_week TINYINT NULLABLE
COMMENT '0=Monday, 1=Tuesday, 2=Wednesday, 3=Thursday, 4=Friday, 5=Saturday, 6=Sunday';
```

## Deployment Steps

1. **Pull the latest code** with the new migration
2. **Run migrations**:
   ```bash
   php artisan migrate
   ```
3. **Access the feature**:
   - Log in to admin panel
   - Go to Gamification → Quest Templates
   - Start creating templates for each day

## Testing Checklist

- [ ] Can view all 7 day templates
- [ ] Can edit each day's template
- [ ] Form validation works (required fields)
- [ ] Type validation works (only valid types allowed)
- [ ] Live preview updates as user types
- [ ] Can save new template
- [ ] Can update existing template
- [ ] Navigation link works in sidebar
- [ ] Empty state shows when no template exists
- [ ] Day names display correctly
- [ ] Rewards display with correct formatting

## API Response Examples

### Get Template by Day
```php
$quest = DailyQuest::getByDayOfWeek(0); // Monday
// Returns DailyQuest model or null
```

### Get All Weekly Templates
```php
$templates = DailyQuest::getWeeklyTemplates();
// Returns array:
// [
//   0 => [
//     'day_name' => 'Monday',
//     'quest' => DailyQuest|null
//   ],
//   1 => [...],
//   ...
// ]
```

## Future Enhancements

- [ ] Auto-generate daily quests based on templates
- [ ] Duplicate template from one day to another
- [ ] Delete templates
- [ ] Quest scheduling (different templates for different weeks)
- [ ] Template groups/sets
- [ ] Analytics on template usage/completion rates

## Troubleshooting

### Templates not showing
- Ensure migration has run: `php artisan migrate`
- Check that database table has `day_of_week` column

### Edit form not working
- Verify routes are correct: `php artisan route:list | grep quests.template`
- Check controller methods exist in AdminController

### Live preview not updating
- Check JavaScript console for errors
- Verify all input IDs are correct in template-edit view

## Files Modified/Created

### Created
- `database/migrations/2026_07_13_160000_add_day_of_week_to_daily_quests_table.php`
- `resources/views/admin/quests/templates.blade.php` - Main dashboard showing all 7 day templates
- `resources/views/admin/quests/template-edit.blade.php` - Edit form with live preview
- `DAILY_QUEST_TEMPLATES_GUIDE.md` - This comprehensive guide

### Modified
- `app/Models/DailyQuest.php` - Added day_of_week support and helper methods
- `app/Http/Controllers/AdminController.php` - Added template management methods
- `routes/web.php` - Added template routes
- `resources/views/layouts/admin.blade.php` - Added "Quest Templates" sidebar link

## Quick Start

1. **Deploy the code** with all migrations
2. **Run migrations**: `php artisan migrate`
3. **Access Admin Panel** → Gamification → Quest Templates
4. **Create templates** for each day of the week
5. **Done!** Templates are ready to use

## API Usage in Application

```php
// Get Monday's template
$mondayQuest = DailyQuest::getByDayOfWeek(0);

// Get all 7 templates
$weeklyTemplates = DailyQuest::getWeeklyTemplates();

// Auto-generate quest for today
$today = now()->dayOfWeek; // 0=Monday, 6=Sunday
$template = DailyQuest::getByDayOfWeek($today);
if ($template) {
    DailyQuest::create([
        'title' => $template->title,
        'description' => $template->description,
        'type' => $template->type,
        'target' => $template->target,
        'reward_exp' => $template->reward_exp,
        'reward_coins' => $template->reward_coins,
        'date' => now()->toDateString(),
    ]);
}
```
