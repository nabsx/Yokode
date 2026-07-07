# YoKode Admin Dashboard

## Pendahuluan

Admin Dashboard adalah panel manajemen terpadu untuk mengelola platform YoKode. Dashboard ini menyediakan kontrol penuh atas pengguna, modul pembelajaran, kategori, kuis, dan gamifikasi.

## Fitur Utama

### 1. Dashboard Overview
- **Statistik Ringkas**: Total pengguna, pengguna aktif, pengguna premium, modul, kuis, dan kategori
- **Top Users**: Daftar 10 pengguna dengan pengalaman tertinggi
- **Recent Users**: Pengguna baru yang baru bergabung
- **Quick Actions**: Akses cepat ke fitur-fitur utama

### 2. User Management
- **List Users**: Lihat semua pengguna dengan filter pencarian
  - Filter berdasarkan nama/email
  - Filter status premium
  - Sorting berdasarkan kolom
- **View User Details**: Lihat profil lengkap pengguna
  - Statistik pembelajaran
  - Informasi gamifikasi
  - Status premium
- **Edit User**: Ubah informasi pengguna
  - Nama dan email
  - Total XP dan coins
  - Status premium
- **Delete User**: Hapus/ban pengguna dari platform

### 3. Module/Lesson Management
- **List Lessons**: Lihat semua modul pembelajaran
  - Filter berdasarkan kategori
  - Pencarian berdasarkan judul
  - Tampilkan tingkat kesulitan
- **Create Lesson**: Tambah modul baru
  - Judul dan deskripsi
  - Pilih kategori
  - Tentukan tingkat kesulitan (easy, medium, hard)
  - Tambahkan konten HTML
- **Edit Lesson**: Ubah informasi modul
- **Delete Lesson**: Hapus modul

### 4. Category Management
- **List Categories**: Lihat semua kategori
- **Create Category**: Tambah kategori baru
  - Nama kategori
  - Emoji icon
  - Deskripsi
  - Urutan tampil
- **Edit Category**: Ubah informasi kategori
- **Delete Category**: Hapus kategori

### 5. Quiz Management
- **List Quizzes**: Lihat semua kuis dengan statistik

### 6. Gamification Management
- **Achievements**: Lihat semua achievement (pencapaian)
- **Shop Items**: Lihat semua item di toko
- **Daily Quests**: Lihat semua daily quest

### 7. Analytics & Reports
- **Premium Statistics**: Total dan active premium users
- **Top Lessons**: 10 modul paling banyak diselesaikan
- **User Growth**: Grafik pertumbuhan pengguna 30 hari terakhir

## Cara Menggunakan

### Setup Awal

1. **Buat Admin User**
   ```php
   // Di dalam tinker atau migration
   $user = User::create([
       'name' => 'Admin',
       'email' => 'admin@example.com',
       'password' => bcrypt('password'),
       'role' => 'admin' // PENTING: Set role sebagai 'admin'
   ]);
   ```

2. **Akses Admin Dashboard**
   - Login dengan akun admin
   - Kunjungi `/admin/dashboard`

### User Management

#### Melihat Daftar User
```
GET /admin/users
```

#### Melihat Detail User
```
GET /admin/users/{id}
```

#### Edit User
```
GET /admin/users/{id}/edit  // Form
PUT /admin/users/{id}        // Update
```

#### Hapus User
```
DELETE /admin/users/{id}
```

### Lesson Management

#### Melihat Daftar Lesson
```
GET /admin/lessons
```

#### Buat Lesson Baru
```
GET /admin/lessons/create    // Form
POST /admin/lessons          // Store
```

#### Edit Lesson
```
GET /admin/lessons/{id}/edit // Form
PUT /admin/lessons/{id}      // Update
```

#### Hapus Lesson
```
DELETE /admin/lessons/{id}
```

### Category Management

#### Melihat Daftar Category
```
GET /admin/categories
```

#### Buat Category Baru
```
GET /admin/categories/create // Form
POST /admin/categories       // Store
```

#### Edit Category
```
GET /admin/categories/{id}/edit // Form
PUT /admin/categories/{id}      // Update
```

#### Hapus Category
```
DELETE /admin/categories/{id}
```

## Keamanan

### Authentication & Authorization

Admin Dashboard dilindungi oleh dua layer keamanan:

1. **Authentication Middleware**: User harus login
2. **Admin Middleware** (`is_admin`): User harus memiliki role `admin`

Jika user tidak memiliki akses admin, akan menerima error 403.

### Soft Deletes

User yang dihapus akan soft-deleted (tidak sepenuhnya dihapus dari database). Ini memungkinkan recovery data jika diperlukan.

## Database Schema

### Users Table
```sql
ALTER TABLE users ADD COLUMN role ENUM('user', 'admin') DEFAULT 'user';
ALTER TABLE users ADD COLUMN deleted_at TIMESTAMP NULL;
```

## Struktur File

```
resources/views/admin/
├── layouts/
│   └── admin.blade.php           # Admin layout
├── dashboard.blade.php           # Dashboard utama
├── users/
│   ├── index.blade.php          # List users
│   ├── show.blade.php           # User detail
│   └── edit.blade.php           # Edit user
├── lessons/
│   ├── index.blade.php          # List lessons
│   ├── create.blade.php         # Create lesson
│   └── edit.blade.php           # Edit lesson
├── categories/
│   ├── index.blade.php          # List categories
│   ├── create.blade.php         # Create category
│   └── edit.blade.php           # Edit category
├── quizzes/
│   └── index.blade.php          # List quizzes
├── achievements/
│   └── index.blade.php          # List achievements
├── shop/
│   └── index.blade.php          # List shop items
├── quests/
│   └── index.blade.php          # List daily quests
└── analytics.blade.php          # Analytics & reports

app/Http/
├── Controllers/
│   └── AdminController.php       # Admin controller
└── Middleware/
    └── IsAdmin.php               # Admin middleware

database/migrations/
└── 2026_07_07_000000_add_role_to_users_table.php

routes/
└── web.php                       # Admin routes
```

## Routes

```php
// Admin Routes (prefix: /admin)
GET    /admin/dashboard              → AdminController@dashboard
GET    /admin/users                  → AdminController@usersIndex
GET    /admin/users/{id}             → AdminController@usersShow
GET    /admin/users/{id}/edit        → AdminController@usersEdit
PUT    /admin/users/{id}             → AdminController@usersUpdate
DELETE /admin/users/{id}             → AdminController@usersDestroy

GET    /admin/lessons                → AdminController@lessonsIndex
GET    /admin/lessons/create         → AdminController@lessonsCreate
POST   /admin/lessons                → AdminController@lessonsStore
GET    /admin/lessons/{id}/edit      → AdminController@lessonsEdit
PUT    /admin/lessons/{id}           → AdminController@lessonsUpdate
DELETE /admin/lessons/{id}           → AdminController@lessonsDestroy

GET    /admin/categories             → AdminController@categoriesIndex
GET    /admin/categories/create      → AdminController@categoriesCreate
POST   /admin/categories             → AdminController@categoriesStore
GET    /admin/categories/{id}/edit   → AdminController@categoriesEdit
PUT    /admin/categories/{id}        → AdminController@categoriesUpdate
DELETE /admin/categories/{id}        → AdminController@categoriesDestroy

GET    /admin/quizzes                → AdminController@quizzesIndex
GET    /admin/achievements           → AdminController@achievementsIndex
GET    /admin/shop-items             → AdminController@shopItemsIndex
GET    /admin/daily-quests           → AdminController@dailyQuestsIndex
GET    /admin/analytics              → AdminController@analytics
```

## Fitur Lanjutan (Future)

- Bulk import lessons dari CSV
- Bulk import quizzes
- User activity logs
- Custom reports
- User role management
- Content moderation
- Performance optimization

## Troubleshooting

### "Unauthorized access. Admin privileges required."
- Pastikan user memiliki role `admin` di database
- Check di table users, kolom `role` harus bernilai `admin`

### Users tidak tampil
- Pastikan users memiliki role `user` (bukan `admin`)
- Check di table users, filter `role = 'user'`

### Migration errors
- Jalankan: `php artisan migrate`
- Pastikan database sudah terhubung

## Support

Untuk pertanyaan atau masalah, hubungi tim development Yokode.
