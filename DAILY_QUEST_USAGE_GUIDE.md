# 📚 Daily Quest Templates - Panduan Penggunaan Admin

## Pendahuluan
Fitur Daily Quest Templates memungkinkan Anda untuk mengatur quest harian yang berbeda untuk setiap hari dalam seminggu. Setiap hari memiliki template quest dengan reward yang dapat disesuaikan.

## 🎯 Fitur Utama

✅ **7 Template Quest** - Satu untuk setiap hari (Minggu-Sabtu)
✅ **Customizable Rewards** - XP dan Coins untuk setiap quest
✅ **Multiple Quest Types** - Login, Lesson Completion, Quiz, Experience Gain, Perfect Score
✅ **Live Preview** - Lihat tampilan quest real-time saat editing
✅ **Auto Assignment** - Quest otomatis diberikan ke semua user
✅ **Flexible Targets** - Set target sesuai kebutuhan

## 🚀 Setup Awal

### Step 1: Run Migration
Pastikan database sudah ter-update dengan migration terbaru:
```bash
php artisan migrate
```

### Step 2: Initialize Templates
Ada 2 cara untuk initialize default templates:

#### Cara A: Via Admin Panel (Recommended untuk Non-Technical)
1. Login sebagai admin
2. Klik menu **"Quest Templates"** di sidebar
3. Klik tombol **"Initialize Default Templates"**
4. Sistem akan membuat 7 template default

#### Cara B: Via Database Seeding (Recommended untuk Technical)
```bash
php artisan db:seed --class=DailyQuestTemplateSeeder
```

## 📋 Cara Kerja

### 1. Template Structure
```
Daily Quest Template
├── Day of Week (0-6)
├── Title
├── Description
├── Type
├── Target
├── Reward XP
└── Reward Coins
```

### 2. Daily Quest Generation Flow
```
Template (Senin) 
    ↓
quests:generate command (atau manual button)
    ↓
DailyQuest dibuat dari template
    ↓
UserQuest dibuat untuk setiap user
    ↓
User menerima quest di aplikasi
```

### 3. User Quest Tracking
```
User membuka app
    ↓
Lihat daily quest untuk hari ini
    ↓
Complete quest (progress tracking)
    ↓
Claim rewards (XP + Coins)
```

## 🎮 Admin Panel Guide

### Akses Quest Templates
1. Login sebagai Admin
2. Klik **"Quest Templates"** di sidebar (icon: 📅)
3. Akan tampil 7 kartu warna untuk setiap hari

### Layout Templates Page

```
┌─────────────────────────────────────────────┐
│ Daily Quest Templates (Info Box)            │
│ Configure daily quests for each day...      │
└─────────────────────────────────────────────┘

┌──────┬──────┬──────┬──────┬──────┬──────┬──────┐
│ 🔴   │ 🔵   │ 🟣   │ 🟢   │ 🟡   │ 🩷   │ 🟣   │
│Minggu│Senin │Selasa│ Rabu │Kamis│Jumat│Sabtu │
├──────┼──────┼──────┼──────┼──────┼──────┼──────┤
│Title │Title │Title │Title │Title │Title │Title │
│Desc..│Desc..│Desc..│Desc..│Desc..│Desc..│Desc..│
│Type  │Type  │Type  │Type  │Type  │Type  │Type  │
│Target│Target│Target│Target│Target│Target│Target│
│XP+🪙 │XP+🪙 │XP+🪙 │XP+🪙 │XP+🪙 │XP+🪙 │XP+🪙 │
│[Edit]│[Edit]│[Edit]│[Edit]│[Edit]│[Edit]│[Edit]│
└──────┴──────┴──────┴──────┴──────┴──────┴──────┘
```

### Edit Template

1. **Klik tombol "Edit"** pada hari yang ingin diubah
2. **Form Edit** akan terbuka dengan field:
   - **Quest Title**: Nama quest (contoh: "Monday Challenge")
   - **Description**: Deskripsi apa yang harus dilakukan
   - **Quest Type**: Pilih dari dropdown
   - **Target**: Jumlah target yang harus dicapai
   - **Reward XP**: Experience points
   - **Reward Coins**: Coins (🪙)

3. **Preview Section**: Lihat tampilan realtime saat mengetik
4. **Save Changes** atau **Cancel**

## 📝 Contoh Use Cases

### Senin - Lesson Focus
```
Title: Monday Lesson Challenge
Description: Complete 1 lesson to start your week!
Type: complete_lesson
Target: 1
Reward XP: 100
Reward Coins: 50
```
**Alasan**: Memotivasi user untuk mulai minggu dengan lesson

### Rabu - Midweek Grind
```
Title: Midweek Experience Boost
Description: Earn 500 XP to keep your momentum!
Type: gain_exp
Target: 500
Reward XP: 200
Reward Coins: 100
```
**Alasan**: Push user di tengah minggu

### Sabtu - Weekend Challenge
```
Title: Weekend Warrior Challenge
Description: Earn 1000 XP this weekend!
Type: gain_exp
Target: 1000
Reward XP: 300
Reward Coins: 150
```
**Alasan**: High reward untuk encourage engagement di weekend

## 🎲 Quest Types Dijelaskan

### 1. **Login** (login)
- **Deskripsi**: User hanya perlu login
- **Use Case**: Build daily habit
- **Target**: Selalu 1
- **Recommended Reward**: 50 XP, 25 Coins
- **Difficulty**: ⭐ Very Easy

### 2. **Complete Lesson** (complete_lesson)
- **Deskripsi**: Selesaikan N lesson
- **Use Case**: Encourage learning
- **Target**: 1, 2, atau 3 lessons
- **Recommended Reward**: 100-250 XP, 50-125 Coins
- **Difficulty**: ⭐⭐ Easy-Medium

### 3. **Answer Quiz** (answer_quiz)
- **Deskripsi**: Jawab N quiz questions
- **Use Case**: Practice dan assessment
- **Target**: 3, 5, 10 questions
- **Recommended Reward**: 150-200 XP, 75-100 Coins
- **Difficulty**: ⭐⭐ Easy-Medium

### 4. **Gain Experience** (gain_exp)
- **Deskripsi**: Kumpulkan N XP points
- **Use Case**: Encourage playtime
- **Target**: 500, 1000, 2000 XP
- **Recommended Reward**: 200-300 XP, 100-150 Coins
- **Difficulty**: ⭐⭐⭐ Medium

### 5. **Perfect Quiz** (perfect_quiz)
- **Deskripsi**: Answer N quizzes dengan score 100%
- **Use Case**: Challenge advanced users
- **Target**: 1, 2, 3 quizzes
- **Recommended Reward**: 180-250 XP, 90-125 Coins
- **Difficulty**: ⭐⭐⭐⭐ Hard

## 💡 Best Practices

### 1. **Balanced Difficulty**
```
Minggu: ⭐ (Login - easy start)
Senin:  ⭐⭐ (Build momentum)
Selasa: ⭐⭐ (Consistent)
Rabu:   ⭐⭐⭐ (Midweek push)
Kamis:  ⭐⭐⭐ (Maintain energy)
Jumat:  ⭐⭐⭐ (Keep going!)
Sabtu:  ⭐⭐⭐⭐ (Maximum reward)
```

### 2. **Progressive Rewards**
```
Mudah:       50-100 XP
Sedang:      150-200 XP
Sulit:       250-300 XP
```

### 3. **Target Settings**
- **Easy**: 1 item
- **Medium**: 2-5 items
- **Hard**: 10+ items or 1000+ XP

### 4. **Variety**
Gunakan berbagai quest types sepanjang minggu untuk:
- Reduce monotony
- Encourage different types of learning
- Maximize engagement

### 5. **Motivational Pattern**
```
Week Pattern:
- Start Week: Easy (build habit)
- Mid Week: Hard (push engagement)
- End Week: Very Hard (maximum reward)
- End Week + High Reward: Celebrate achievement
```

## 🔄 Automatic Generation

### Generate untuk Hari Ini
```bash
php artisan quests:generate
```

### Generate untuk Tanggal Spesifik
```bash
php artisan quests:generate --date=2026-07-14
```

### Setup Automatic Daily Generation
Edit file `app/Console/Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    // Generate daily quests setiap hari jam 00:00
    $schedule->command('quests:generate')
        ->daily()
        ->runInBackground();
    
    // Atau spesifik waktu tertentu
    $schedule->command('quests:generate')
        ->dailyAt('00:00')
        ->timezone('Asia/Jakarta');
}
```

Pastikan server cronjob berjalan:
```bash
# Untuk Linux/Mac
*/5 * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

## 📊 Monitoring & Analytics

### Check Generated Quests
```bash
mysql> SELECT * FROM daily_quests 
       WHERE date >= CURDATE() - INTERVAL 7 DAY 
       ORDER BY date DESC;
```

### Check User Quest Progress
```bash
mysql> SELECT u.name, dq.title, uq.progress, uq.completed 
       FROM user_quests uq
       JOIN users u ON u.id = uq.user_id
       JOIN daily_quests dq ON dq.id = uq.daily_quest_id
       WHERE uq.date = CURDATE();
```

### Check Templates
```bash
mysql> SELECT day_of_week, title, type, target, reward_exp, reward_coins 
       FROM daily_quest_templates 
       ORDER BY day_of_week;
```

## ⚠️ Common Issues & Solutions

### Issue: Templates tidak muncul
**Solution**:
1. Klik "Initialize Default Templates" button
2. Atau run: `php artisan db:seed --class=DailyQuestTemplateSeeder`

### Issue: Changes tidak ter-save
**Solution**:
1. Check untuk validation errors
2. Ensure form fields terisi dengan benar
3. Check server logs

### Issue: Users tidak menerima quest
**Solution**:
1. Run: `php artisan quests:generate`
2. Check user role (harus 'user', bukan 'admin')
3. Check database: `SELECT * FROM user_quests WHERE date = CURDATE();`

### Issue: Old quests masih tampil
**Solution**:
- Quests untuk tanggal lampau tidak bisa diedit
- System hanya show current day quest
- Jika perlu ubah, create new template untuk hari depan

## 🎓 Learning Path

1. **Day 1**: Initialize templates default
2. **Day 2**: Edit beberapa templates
3. **Day 3**: Setup automatic generation via cronjob
4. **Day 4**: Monitor user completion rates
5. **Day 5**: Adjust difficulties based on data

## 📞 Need Help?

Lihat dokumentasi lengkap:
- `DAILY_QUEST_TEMPLATES.md` - Technical details
- `IMPLEMENTATION_SUMMARY.md` - Implementation overview

---

**Happy Quest Making!** 🎮✨
