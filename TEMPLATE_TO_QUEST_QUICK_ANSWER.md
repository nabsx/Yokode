# Template → Quest: Jawaban Lengkap

## ❓ Pertanyaan
**"Apakah quest template itu bisa dipakai menjadi daily quest?"**

## ✅ Jawaban
**YA! Template otomatis menjadi daily quest setiap hari.**

---

## 🎯 Singkatnya

```
Admin setup template (Senin = "Login Challenge")
           ↓ (setiap hari jam 00:00)
Scheduler mengambil template
           ↓
Command membuat DailyQuest baru
           ↓
Assign ke semua users
           ↓
Users lihat quest di app dan complete
```

---

## 📊 Contoh Konkret

### Hari Senin

**Admin Dashboard** (`/admin/quest-templates`):
- Template untuk Senin: "Login Challenge"
  - Target: Login 1x
  - Reward: 50 XP + 25 coins

**Jam 00:00 (Tengah Malam)**:
- Scheduler otomatis jalankan `quests:generate`
- System baca template Senin
- Buat DailyQuest baru untuk Senin
- Assign ke 1000+ users

**Pagi hari, Users lihat**:
- Quest muncul: "Login Challenge"
- User login → Quest complete
- User terima: 50 XP + 25 coins

**Jam 23:59 (Akhir hari)**:
- Quest masih bisa di-complete sampai tengah malam

### Hari Selasa (00:00)

- Scheduler ambil template Selasa
- Buat quest baru: "Quiz Master"
- Assign ke semua users
- Quest Senin tidak lagi muncul (expired)
- Users sekarang lihat quest Selasa

---

## 🔧 Setup (3 Langkah)

### 1️⃣ Run Migration
```bash
php artisan migrate
```
Membuat tabel `daily_quest_templates` dan `daily_quests`.

### 2️⃣ Initialize Templates
```bash
# Opsi A: Via CLI
php artisan db:seed --class=DailyQuestTemplateSeeder

# Opsi B: Via Admin Panel
Login → Quest Templates → "Initialize Default Templates"
```
Membuat 7 template (satu per hari).

### 3️⃣ Setup Scheduler
```bash
# Edit crontab
crontab -e

# Add this line
* * * * * cd /path/to/yokode && php artisan schedule:run >> /dev/null 2>&1
```
Membuat quest otomatis setiap hari.

**Done!** Sekarang template akan otomatis menjadi quest setiap hari. ✅

---

## 🧪 Coba Sekarang (Testing)

### Manual Test (di terminal):
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

### Cek Database:
```bash
php artisan tinker

>>> App\Models\DailyQuest::latest()->first()
// Lihat quest yang baru dibuat dengan date hari ini

>>> App\Models\UserQuest::latest()->first()
// Lihat quest yang di-assign ke user
```

---

## 🎮 Alur Lengkap

```
MONDAY 00:00 (Tengah Malam)
├─ Scheduler runs
├─ Get template for Monday
├─ Create DailyQuest
├─ Assign to all users
└─ ✓ Done

MONDAY 08:00 - 23:59 (User Time)
├─ User sees quest: "Login Challenge"
├─ User completes it
├─ User gets: 50 XP + 25 coins
└─ Progress saved

TUESDAY 00:00 (Tengah Malam)
├─ Scheduler runs
├─ Get template for Tuesday
├─ Create NEW DailyQuest (different from Monday)
├─ Assign to all users
├─ Monday quest expires (no longer visible)
└─ ✓ Done

TUESDAY 08:00 - 23:59 (User Time)
├─ User sees quest: "Quiz Master" (different from Monday!)
├─ Previous quest gone
└─ Cycle repeats...
```

---

## 📋 Database Tables

### Tabel 1: `daily_quest_templates` (Setup Admin)
```
Senin    → "Login Challenge" (50 XP, 25 🪙)
Selasa   → "Quiz Master" (150 XP, 75 🪙)
Rabu     → "Experience Grinder" (200 XP, 100 🪙)
...
(7 templates total - setup once)
```

### Tabel 2: `daily_quests` (Created by Scheduler)
```
2026-07-13 (Sunday)  → "Login Challenge" (dari template Minggu)
2026-07-14 (Monday)  → "Login Challenge" (dari template Senin)
2026-07-15 (Tuesday) → "Quiz Master" (dari template Selasa)
...
(1 per hari - auto-generated)
```

### Tabel 3: `user_quests` (User Progress)
```
User #1 + Quest #1 (2026-07-14) → progress: 1/1, completed: true
User #2 + Quest #1 (2026-07-14) → progress: 0/1, completed: false
User #3 + Quest #2 (2026-07-15) → progress: 3/5, completed: false
...
(ribuan records - auto-assigned)
```

---

## 💡 Key Points

✅ **Template** = Blueprint (setup admin sekali)
✅ **DailyQuest** = Instance nyata untuk hari spesifik
✅ **UserQuest** = Tracking progress individual user
✅ **Scheduler** = Automation (template → quest setiap hari)

---

## ❌ Apa yang TIDAK Bisa Dilakukan

❌ Template tidak bisa langsung di-assign ke users (harus jadi DailyQuest dulu)
❌ Satu template hanya untuk satu hari spesifik (tidak bisa Senin + Selasa)
❌ Manual create DailyQuest dari template (pake command/scheduler)

---

## ✨ Keuntungan Sistem Ini

🎯 **Fleksibel**: Admin bisa ubah template kapan saja
🎯 **Otomatis**: Tidak perlu manual create quest setiap hari
🎯 **Scalable**: Bisa ribuan users tanpa masalah
🎯 **Trackable**: Lihat history quest setiap hari
🎯 **Configurable**: 5 jenis quest, 7 hari, rewards berbeda-beda

---

## 📚 Dokumentasi Lengkap

Baca untuk info lebih detail:
- `TEMPLATE_TO_DAILY_QUEST_FLOW.md` - Alur lengkap
- `SCHEDULER_SETUP_GUIDE.md` - Setup scheduler
- `QUEST_INTEGRATION_EXAMPLES.md` - Code examples

---

## ⚡ TL;DR (Sangat Singkat)

```
Template ────────────→ Daily Quest ────────→ User
(Setup)     (Scheduler)  (Auto-created)  (Receive & Complete)

Di Sistem Yokode:
1. Admin setup 7 templates di admin panel ✓
2. Scheduler jalankan command setiap hari ✓
3. Command create quest dari template ✓
4. Users auto-receive quest ✓
5. Repeat setiap hari ✓
```

---

**Status**: ✅ READY & WORKING
**Next Step**: Setup scheduler on your server
