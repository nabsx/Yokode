# Daily Quest Templates - Ringkasan Implementasi

## 📋 Overview
Fitur ini memungkinkan admin untuk mengatur template daily quest untuk setiap hari dalam seminggu (Senin-Minggu). Setiap hari dapat memiliki quest yang berbeda dengan target dan reward yang unik.

## 🗂️ Files Created/Modified

### 1. Database
- **Migration**: `/database/migrations/2026_07_13_160000_create_daily_quest_templates_table.php`
  - Membuat tabel `daily_quest_templates` dengan struktur untuk template
  - Unique constraint pada `day_of_week`

- **Seeder**: `/database/seeders/DailyQuestTemplateSeeder.php`
  - Seeder untuk inisialisasi 7 template default
  - Bisa dijalankan dengan: `php artisan db:seed --class=DailyQuestTemplateSeeder`

### 2. Models
- **DailyQuestTemplate**: `/app/Models/DailyQuestTemplate.php`
  - Model untuk mengelola daily quest templates
  - Methods: `getDayName()`, `getDayNameEn()`, `generateQuestForDay()`

### 3. Controllers
- **AdminController**: `/app/Http/Controllers/AdminController.php` (modified)
  - Method tambahan:
    - `questTemplatesIndex()` - Tampilkan semua templates
    - `questTemplatesEdit()` - Edit form untuk template
    - `questTemplatesUpdate()` - Update template
    - `questTemplatesInitialize()` - Inisialisasi default templates

### 4. Routes
- **Routes**: `/routes/web.php` (modified)
  - 4 route baru untuk daily quest templates management
  - Routes:
    - `GET /admin/quest-templates` - List
    - `GET /admin/quest-templates/{id}/edit` - Edit form
    - `PUT /admin/quest-templates/{id}` - Update
    - `POST /admin/quest-templates/initialize` - Initialize defaults

### 5. Views
- **templates.blade.php**: `/resources/views/admin/quests/templates.blade.php`
  - Grid display dengan 7 kartu untuk setiap hari
  - Color-coded cards berdasarkan hari
  - Initialize button untuk seeding default

- **template-edit.blade.php**: `/resources/views/admin/quests/template-edit.blade.php`
  - Form untuk edit template
  - Live preview dengan JavaScript
  - Validation error messages

### 6. Console Commands
- **GenerateDailyQuests**: `/app/Console/Commands/GenerateDailyQuests.php`
  - Command untuk generate daily quests dari templates
  - Syntax: `php artisan quests:generate [--date=Y-m-d]`
  - Auto-assign ke semua active users

### 7. Helpers
- **QuestHelper**: `/app/Helpers/QuestHelper.php`
  - Static helper methods untuk quest operations
  - Methods: `generateDailyQuests()`, `assignQuestToUsers()`, `getTodayQuest()`, dll.
  - Reusable di controllers dan services

### 8. Documentation
- **DAILY_QUEST_TEMPLATES.md**: Complete documentation
- **IMPLEMENTATION_SUMMARY.md**: File ini

### 9. Layout
- **admin.blade.php**: `/resources/views/layouts/admin.blade.php` (modified)
  - Tambah menu item "Quest Templates" di sidebar

## 🚀 Quick Start

### 1. Run Migration
```bash
php artisan migrate
```

### 2. Option A: Run Seeder (Recommended)
```bash
php artisan db:seed --class=DailyQuestTemplateSeeder
```

### 3. Option B: Initialize via Admin Panel
- Login sebagai admin
- Go to "Quest Templates" menu
- Click "Initialize Default Templates" button

### 4. Edit Templates
- Admin dapat mengedit setiap template per hari
- Live preview membantu visualisasi perubahan

### 5. Auto-Generate Daily Quests
#### Manual (untuk testing):
```bash
php artisan quests:generate --date=2026-07-14
```

#### Automatic (Setup Scheduler):
Edit `app/Console/Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('quests:generate')->daily();
}
```

## 📊 Default Templates

| Hari | Quest | Type | Target | Reward |
|------|-------|------|--------|--------|
| Minggu | Login Challenge | login | 1 | 50 XP, 25 🪙 |
| Senin | Lesson Completion | complete_lesson | 1 | 100 XP, 50 🪙 |
| Selasa | Quiz Master | answer_quiz | 5 | 150 XP, 75 🪙 |
| Rabu | Experience Grinder | gain_exp | 500 | 200 XP, 100 🪙 |
| Kamis | Perfect Score | perfect_quiz | 3 | 180 XP, 90 🪙 |
| Jumat | Lesson Marathon | complete_lesson | 2 | 250 XP, 125 🪙 |
| Sabtu | Weekend Warrior | gain_exp | 1000 | 300 XP, 150 🪙 |

## 🔧 Configuration

### Quest Types Available
- `login` - User harus login
- `complete_lesson` - Complete N lessons
- `answer_quiz` - Answer N quiz questions
- `gain_exp` - Gain N experience points
- `perfect_quiz` - Answer N quizzes dengan score 100%

### Customization
1. **Edit Default Templates**: Ubah seeder atau init via admin panel
2. **Add New Quest Types**: 
   - Update migration enum: `['complete_lesson', 'answer_quiz', ...]`
   - Update model migration
   - Update views

3. **Change Reward Distribution**: 
   - Edit template via admin panel
   - Atau ubah seeder dan re-seed

## 🔌 Integration Points

### With UserQuest
- Template ➜ Daily Quest ➜ User Quest
- Ketika quest di-generate, otomatis di-assign ke semua users

### With User Model
- UserQuest sudah link ke User model
- Quest tracking per user

### With Gamification System
- Rewards (XP, Coins) terintegrasi dengan existing system
- User achievement tracking compatible

## ✅ Testing

### Test CLI Command
```bash
# Generate untuk hari ini
php artisan quests:generate

# Generate untuk tanggal spesifik
php artisan quests:generate --date=2026-07-15

# Check output - should see created quest
```

### Test Admin Panel
1. Go to `/admin/quest-templates`
2. Click "Initialize Default Templates" jika belum ada
3. Lihat 7 cards untuk setiap hari
4. Click "Edit" dan modify satu template
5. Save dan verify perubahan

### Test Database
```sql
-- Check templates
SELECT * FROM daily_quest_templates ORDER BY day_of_week;

-- Check generated quests
SELECT * FROM daily_quests WHERE date = CURDATE();

-- Check user quests
SELECT * FROM user_quests WHERE date = CURDATE() LIMIT 10;
```

## 📝 Usage Examples

### In Controller
```php
use App\Helpers\QuestHelper;

// Generate daily quests
$quest = QuestHelper::generateDailyQuests();

// Get today's quest
$todayQuest = QuestHelper::getTodayQuest();

// Get user's today quest
$userQuest = QuestHelper::getUserTodayQuest($user);

// Get template for specific day (0-6)
$template = QuestHelper::getTemplateForDay(1); // Monday
```

### In Command/Job
```php
use App\Helpers\QuestHelper;

$date = Carbon::now();
QuestHelper::generateDailyQuests($date);
```

## 🐛 Troubleshooting

### Templates tidak muncul di admin panel
1. Check migration: `php artisan migrate --step`
2. Check table: `SELECT COUNT(*) FROM daily_quest_templates;`
3. Initialize: Click button di admin panel

### Quests tidak auto-generate
1. Check command: `php artisan quests:generate`
2. Check cronjob scheduler is running
3. Manual: Run command with specific date

### User tidak terima quest
1. Ensure user role is 'user', not 'admin'
2. Check: `SELECT * FROM user_quests WHERE user_id = X AND date = CURDATE();`

## 🎯 Future Enhancements
- [ ] Multiple quests per day
- [ ] Seasonal/Event templates
- [ ] User difficulty levels
- [ ] Quest completion analytics
- [ ] Template version history
- [ ] Template bulk import/export

## 📞 Support
Untuk bantuan lebih lanjut, lihat dokumentasi lengkap di `DAILY_QUEST_TEMPLATES.md`
