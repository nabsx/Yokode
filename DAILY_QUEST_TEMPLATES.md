# Daily Quest Templates - Admin Documentation

## Overview
Daily Quest Templates adalah fitur yang memungkinkan admin untuk mengatur quest harian untuk setiap hari dalam seminggu (Senin-Minggu). Setiap hari memiliki template quest yang unik dengan reward berbeda.

## Features
- ✅ Template quest untuk setiap hari (0-6, Minggu-Sabtu)
- ✅ Admin dapat mengedit template untuk setiap hari
- ✅ Automatic generation dari quest berdasarkan template
- ✅ Default templates yang dapat diinisialisasi
- ✅ Live preview saat editing
- ✅ Flexible reward system (XP dan Coins)

## Struktur Database

### Tabel: `daily_quest_templates`
```sql
- id: Primary Key
- day_of_week: Integer (0=Minggu, 1=Senin, ..., 6=Sabtu)
- title: String - Judul quest
- description: Text - Deskripsi quest
- type: Enum - Tipe quest (complete_lesson, answer_quiz, gain_exp, login, perfect_quiz)
- target: Integer - Target yang harus dicapai
- reward_exp: Integer - Reward experience points
- reward_coins: Integer - Reward coins
- timestamps: created_at, updated_at
- unique constraint: [day_of_week]
```

## Models

### DailyQuestTemplate
Model untuk mengelola daily quest templates.

**Methods:**
- `getDayName()` - Mendapatkan nama hari dalam Bahasa Indonesia
- `getDayNameEn()` - Mendapatkan nama hari dalam Bahasa Inggris
- `generateQuestForDay($dayOfWeek, $date)` - Membuat DailyQuest dari template

## Routes

### Admin Routes
```
GET    /admin/quest-templates              - Tampilkan daftar templates
GET    /admin/quest-templates/{id}/edit    - Edit form untuk template
PUT    /admin/quest-templates/{id}         - Update template
POST   /admin/quest-templates/initialize   - Inisialisasi default templates
```

## Admin Panel Usage

### 1. Akses Quest Templates
1. Login sebagai admin
2. Klik menu "Quest Templates" di sidebar
3. Akan menampilkan 7 kartu untuk setiap hari dalam seminggu

### 2. Inisialisasi Default Templates (First Time)
Jika belum ada template:
1. Klik tombol "Initialize Default Templates"
2. Sistem akan membuat 7 template default untuk setiap hari
3. Default templates dapat diedit sesuai kebutuhan

### 3. Edit Template Quest
1. Klik tombol "Edit" pada kartu hari yang ingin diedit
2. Ubah informasi quest:
   - **Title**: Nama quest
   - **Description**: Deskripsi quest
   - **Type**: Jenis quest (pilihan: complete_lesson, answer_quiz, gain_exp, login, perfect_quiz)
   - **Target**: Target yang harus dicapai (contoh: 5 untuk answer 5 quizzes)
   - **Reward XP**: Experience points yang diberikan
   - **Reward Coins**: Coins yang diberikan
3. Lihat preview real-time saat editing
4. Klik "Save Changes" untuk menyimpan

## Console Commands

### Generate Daily Quests
Perintah untuk generate daily quest dari template untuk tanggal tertentu.

```bash
# Generate untuk hari ini
php artisan quests:generate

# Generate untuk tanggal spesifik
php artisan quests:generate --date=2026-07-14
```

Perintah ini akan:
1. Mengambil template untuk hari tersebut
2. Membuat `DailyQuest` entry
3. Assign quest ke semua active users

### Scheduling
Untuk automatic generation setiap hari, tambahkan ke `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Generate daily quests setiap hari pada jam 00:00
    $schedule->command('quests:generate')->daily();
}
```

## Quest Types

| Type | Deskripsi | Contoh Target |
|------|-----------|----------------|
| `complete_lesson` | Selesaikan lesson | 1, 2, 3 |
| `answer_quiz` | Jawab quiz question | 5, 10, 15 |
| `gain_exp` | Dapatkan experience | 500, 1000 |
| `login` | Login ke platform | 1 |
| `perfect_quiz` | Answer quiz dengan sempurna (score 100) | 1, 2, 3 |

## Default Templates

Sistem menyediakan 7 default templates:

| Hari | Quest | Type | Target | Reward |
|------|-------|------|--------|--------|
| Minggu | Login Challenge | login | 1 | 50 XP, 25 🪙 |
| Senin | Lesson Completion | complete_lesson | 1 | 100 XP, 50 🪙 |
| Selasa | Quiz Master | answer_quiz | 5 | 150 XP, 75 🪙 |
| Rabu | Experience Grinder | gain_exp | 500 | 200 XP, 100 🪙 |
| Kamis | Perfect Score | perfect_quiz | 3 | 180 XP, 90 🪙 |
| Jumat | Lesson Marathon | complete_lesson | 2 | 250 XP, 125 🪙 |
| Sabtu | Weekend Warrior | gain_exp | 1000 | 300 XP, 150 🪙 |

## Integration dengan UserQuest

Ketika quest di-generate:
1. `DailyQuest` dibuat dari template
2. `UserQuest` entry dibuat untuk setiap user
3. User dapat track progress dan claim rewards

## Best Practices

1. **Balanced Rewards**: Sesuaikan reward berdasarkan kesulitan quest
2. **Gradual Difficulty**: Mulai mudah di awal minggu, semakin sulit di akhir minggu
3. **Variety**: Gunakan berbagai tipe quest untuk engagement
4. **Consistency**: Update template secara berkala untuk keep users engaged

## Troubleshooting

### Templates tidak muncul
- Pastikan sudah klik "Initialize Default Templates"
- Check database: `SELECT * FROM daily_quest_templates;`

### Quests tidak auto-generate
- Setup scheduler di `app/Console/Kernel.php`
- Ensure cronjob server berjalan
- Manual run: `php artisan quests:generate --date=YYYY-MM-DD`

### User tidak menerima quests
- Check: `SELECT * FROM user_quests WHERE date = CURDATE();`
- Verify user role is 'user', not 'admin'

## Future Enhancements
- [ ] Multiple quests per day
- [ ] Seasonal templates
- [ ] User difficulty levels
- [ ] Quest completion analytics
- [ ] Quest difficulty badges
