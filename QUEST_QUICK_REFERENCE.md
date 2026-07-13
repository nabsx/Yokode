# ⚡ Daily Quest Templates - Quick Reference Card

## 🎯 What is it?
Admin dapat mengatur 7 template daily quest untuk setiap hari dalam seminggu dengan reward yang berbeda.

## 📍 Access Points
- **Admin Panel**: `/admin/quest-templates`
- **Menu**: Sidebar → "Quest Templates" (icon: 📅)
- **Route Prefix**: `admin.quest-templates.*`

## 🚀 Quick Start (3 Steps)

### 1️⃣ Run Migration
```bash
php artisan migrate
```

### 2️⃣ Initialize Templates
**Via Admin Panel:**
- Login → Quest Templates → "Initialize Default Templates"

**Via CLI:**
```bash
php artisan db:seed --class=DailyQuestTemplateSeeder
```

### 3️⃣ Generate Quests
**Manual:**
```bash
php artisan quests:generate
```

**Automatic (Setup):**
```php
// app/Console/Kernel.php
$schedule->command('quests:generate')->daily();
```

## 📊 Day Map
| # | Day | 🏴 | Default Quest |
|---|-----|-----|---|
| 0 | Minggu | 🔴 | Login Challenge |
| 1 | Senin | 🔵 | Lesson Completion |
| 2 | Selasa | 🟣 | Quiz Master |
| 3 | Rabu | 🟢 | Experience Grinder |
| 4 | Kamis | 🟡 | Perfect Score |
| 5 | Jumat | 🩷 | Lesson Marathon |
| 6 | Sabtu | 🟣 | Weekend Warrior |

## 🎮 Quest Types
| Type | Icon | Description | Use |
|------|------|-------------|-----|
| login | 🔓 | User login | Daily habit |
| complete_lesson | 📖 | Complete N lessons | Learning |
| answer_quiz | ❓ | Answer N quizzes | Assessment |
| gain_exp | ⭐ | Gain N XP | Playtime |
| perfect_quiz | 💯 | N perfect scores | Challenge |

## 📝 Fields to Edit
- `title` - Quest name
- `description` - What to do
- `type` - Quest type (dropdown)
- `target` - Target value
- `reward_exp` - Experience points
- `reward_coins` - Coins (🪙)

## 📁 Key Files

### Models
- `DailyQuestTemplate.php` - Template model
- Related: `DailyQuest.php`, `UserQuest.php`

### Controllers
- `AdminController@questTemplates*` - Admin methods

### Commands
- `GenerateDailyQuests` - Generate quests from templates

### Views
- `admin/quests/templates.blade.php` - Template list
- `admin/quests/template-edit.blade.php` - Edit form

### Helpers
- `QuestHelper.php` - Static helper methods

### Database
- `daily_quest_templates` table

## 💻 Database Schema
```sql
CREATE TABLE daily_quest_templates (
    id BIGINT PRIMARY KEY,
    day_of_week INT (0-6),
    title VARCHAR(255),
    description TEXT,
    type ENUM(...),
    target INT,
    reward_exp INT,
    reward_coins INT,
    created_at, updated_at
);
```

## 🔧 Helper Methods

### Generate Quest
```php
use App\Helpers\QuestHelper;

$quest = QuestHelper::generateDailyQuests();
$quest = QuestHelper::generateDailyQuests($date);
```

### Get Quest
```php
$todayQuest = QuestHelper::getTodayQuest();
$userQuest = QuestHelper::getUserTodayQuest($user);
$template = QuestHelper::getTemplateForDay(1);
$allTemplates = QuestHelper::getAllTemplates();
```

### Utility
```php
$dayName = QuestHelper::getDayNameId(0); // "Minggu"
$dayName = QuestHelper::getDayNameEn(0); // "Sunday"
```

### Initialize
```php
QuestHelper::initializeDefaults();
```

## 🎯 Common Tasks

### Edit Monday's Quest
1. Admin Panel → Quest Templates
2. Click "Edit" on Senin card
3. Modify fields
4. Click "Save Changes"

### Change All Rewards
1. Go to each day
2. Edit reward_exp and reward_coins
3. Or edit seeder and re-seed

### Generate For Specific Date
```bash
php artisan quests:generate --date=2026-07-20
```

### Check Progress
```sql
SELECT * FROM user_quests WHERE date = CURDATE();
SELECT * FROM daily_quests WHERE date = CURDATE();
SELECT * FROM daily_quest_templates ORDER BY day_of_week;
```

## ⚙️ Routes Summary
| Method | Route | Handler | Purpose |
|--------|-------|---------|---------|
| GET | /admin/quest-templates | questTemplatesIndex | List all |
| GET | /admin/quest-templates/{id}/edit | questTemplatesEdit | Edit form |
| PUT | /admin/quest-templates/{id} | questTemplatesUpdate | Save changes |
| POST | /admin/quest-templates/initialize | questTemplatesInitialize | Init defaults |

## 🎨 UI Components

### Template Cards
```
[Day Name] [Day Eng]
Title
Description...
Type | Target | XP + Coins
[Edit Button]
```

### Edit Form
```
Day: Senin (Monday)
Title: [text input]
Description: [textarea]
Type: [dropdown]
Target: [number]
Reward XP: [number]
Reward Coins: [number]
[Preview Box]
[Save] [Cancel]
```

## 🧪 Testing

### Create Seeder
```bash
php artisan make:seeder DailyQuestTemplateSeeder
```

### Run Tests
```bash
php artisan test
```

## ⚠️ Important Notes

❌ **Cannot edit:**
- Templates for past dates (auto-generate only works for future/today)
- After quest is generated, template edit doesn't affect existing quests

✅ **Can do:**
- Edit template to affect next generation
- Manually create DailyQuest entries
- Override rewards after generation

## 🔄 Flow Diagram

```
DailyQuestTemplate (Template Setup)
         ↓
   Admin edits daily
         ↓
   quests:generate (Command)
         ↓
   DailyQuest created for today
         ↓
   UserQuest created for each user
         ↓
   User completes quest
         ↓
   Claim rewards (XP + Coins)
```

## 📚 Documentation Files

- `DAILY_QUEST_TEMPLATES.md` - Full technical docs
- `DAILY_QUEST_USAGE_GUIDE.md` - Admin guide with examples
- `QUEST_INTEGRATION_EXAMPLES.md` - Code examples for developers
- `IMPLEMENTATION_SUMMARY.md` - Implementation overview

## 🆘 Troubleshooting Quick Links

| Problem | Solution |
|---------|----------|
| Templates not showing | Click "Initialize Default Templates" |
| Quests not generating | Run `php artisan quests:generate` |
| Users not receiving | Check user role = 'user' |
| Changes not saving | Check validation errors |
| Old quests still showing | Quests are date-locked after creation |

## 🚀 Pro Tips

1. **Balanced Progression**: Start easy → harder at week end
2. **Variety**: Mix quest types throughout the week
3. **Sweet Spot**: Target 15-30 min per quest
4. **Reward Scale**: Easy:50 XP → Hard:300 XP
5. **Monitor**: Check completion rates weekly

## 📱 Mobile API Ready
Routes available for mobile app:
- `GET /api/quests/today`
- `POST /api/quests/{id}/progress`
- `POST /api/quests/{id}/claim`
- `GET /api/quests/weekly`

---

**For more info, see:** 📖 Documentation files or contact admin team

**Last Updated:** 2026-07-13
