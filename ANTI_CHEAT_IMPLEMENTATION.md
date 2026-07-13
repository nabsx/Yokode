# Anti-Cheat System Implementation Guide

## Ringkasan Fitur
Sistem anti-curang telah diimplementasikan untuk mencegah user melakukan curang saat mengerjakan course dengan cara melihat jawaban dan penjelasan, kemudian mencoba ulang untuk menjawab dengan benar.

## Cara Kerja

### 1. Mekanisme Terkunci (Locked)
Ketika user menjawab quiz dengan **salah**, sistem akan:
1. Menampilkan jawaban benar dan penjelasan (reason)
2. Menandai quiz sebagai "terkunci" (is_viewed_reason = true)
3. Mencatat attempt_number untuk tracking

### 2. Preventif Retry
Jika user mencoba untuk menjawab quiz yang sudah terkunci:
1. Request ke server akan ditolak dengan pesan: "🔒 Anda sudah melihat jawaban untuk soal ini. Tidak bisa mencoba lagi."
2. Quiz akan menjadi non-interaktif di halaman lesson
3. User hanya bisa melihat jawaban sebelumnya (read-only)

### 3. Dashboard Status
Di halaman dashboard, lesson dengan quiz yang terkunci akan:
1. Ditampilkan dengan status "🔒 Anti-Curang Aktif"
2. Tidak bisa diklik/dibuka
3. Menampilkan pesan: "Modul terkunci karena ada soal yang sudah dijawab salah"

## Database Changes

### Kolom Baru di `user_answers` Table
```sql
- is_viewed_reason (boolean, default: false)
  → Track apakah user sudah melihat penjelasan jawaban salah

- attempt_number (integer, default: 1)
  → Track percobaan ke berapa

- locked_until (timestamp, nullable)
  → Reserved untuk future feature (cooldown retry)
```

### Migration File
`database/migrations/2026_07_13_000000_add_anti_cheat_fields_to_user_answers_table.php`

Jalankan: `php artisan migrate`

## File yang Diubah

### 1. Models
- **UserAnswer.php**: Tambah fillable dan casts untuk field baru
- **Quiz.php**: Tambah method `canRetry()` dan `getStatusForUser()`

### 2. Controllers
- **QuizController.php**: 
  - Tambah validasi anti-curang di `submitAnswer()`
  - Set `is_viewed_reason = true` saat jawaban salah
  - Track `attempt_number`

- **LessonController.php**:
  - Fetch status quiz untuk setiap quiz di lesson
  - Return `quizzesWithStatus` ke view dengan status dan `can_retry` flag

- **DashboardController.php**:
  - Tambah `getLessonStatus()` method untuk cek status lesson
  - Return status 'available', 'locked', atau 'completed'

### 3. Views
- **resources/views/lessons/show.blade.php**:
  - Tampilkan UI khusus untuk quiz yang terkunci (red border, disabled buttons)
  - Tampilkan pesan warning dan jawaban sebelumnya
  - Handle error response ketika quiz terkunci di JavaScript

- **resources/views/dashboard.blade.php**:
  - Tampilkan badge "🔒 Anti-Curang Aktif" untuk lesson yang terkunci
  - Disable link untuk lesson yang terkunci
  - Tampilkan warning message

## Status Quiz
Quiz memiliki 3 status:

### 1. Available
- Belum pernah dijawab, atau
- Dijawab salah tapi belum melihat penjelasan

### 2. Locked
- Dijawab salah dan sudah melihat penjelasan
- **User TIDAK bisa retry**

### 3. Completed
- Dijawab dengan benar
- User bisa lihat tapi tidak perlu retry

## Features

### Untuk User
- ✅ Melihat feedback langsung setelah menjawab
- ✅ Melihat jawaban benar dan penjelasan jika menjawab salah
- ✅ TIDAK bisa retry setelah melihat jawaban salah
- ✅ Bisa melihat history jawaban mereka (read-only)

### Untuk Admin/Dashboard
- ✅ Lihat status quiz setiap user
- ✅ Lihat status lesson (available/locked/completed)
- ✅ Lihat attempt_number untuk tracking
- ✅ Future: Cooldown retry dengan locked_until timestamp

## Testing Checklist

1. **Test Quiz Flow:**
   - [ ] User menjawab quiz dengan benar → berhasil
   - [ ] User menjawab quiz dengan salah → lihat jawaban benar + reason
   - [ ] User coba retry quiz yang sudah dijawab salah → ditolak dengan pesan error

2. **Test UI:**
   - [ ] Lesson page menampilkan quiz terkunci dengan styling khusus
   - [ ] Quiz terkunci tidak bisa di-klik/disabled
   - [ ] Dashboard menampilkan lesson dengan status terkunci
   - [ ] Lesson terkunci tidak bisa dibuka dari dashboard

3. **Test Database:**
   - [ ] is_viewed_reason = true setelah melihat reason
   - [ ] attempt_number increment setiap kali submit
   - [ ] Status query bekerja dengan baik

## Notes untuk Developer

1. **Backward Compatibility**: Existing data akan safe karena field baru punya default value
2. **Caching**: Jika menggunakan cache, perlu invalidate setelah submit answer
3. **API Response**: Tambahkan `is_locked` flag di response untuk JavaScript handling
4. **Future Enhancement**: 
   - Cooldown timer dengan `locked_until`
   - Admin unlock feature
   - Retry request system

## Deployment Steps

1. Backup database
2. Run migration: `php artisan migrate`
3. Test staging environment
4. Deploy ke production
5. Monitor error logs

## Support

Jika ada bugs atau pertanyaan, silakan hubungi developer.
