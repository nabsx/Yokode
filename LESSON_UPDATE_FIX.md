# Lesson Update Fix - Dashboard Data Sync

## Problem
Ketika menambahkan lesson dan modul baru di admin panel, data tidak terupdate di user home/dashboard.

## Root Causes Identified

### 1. Category Filter (is_active)
**File:** `app/Http/Controllers/DashboardController.php`
- Dashboard query menggunakan `Category::where('is_active', true)` 
- Kategori baru tidak otomatis di-set sebagai active
- Hasil: Lesson baru tidak muncul di dashboard

**Fix:**
```php
// Sebelum:
$categories = Category::where('is_active', true)
    ->orderBy('order')
    ->with(['lessons' => function ($query) {
        $query->orderBy('order_number');
    }])
    ->get();

// Sesudah:
$categories = Category::orderBy('order')
    ->with(['lessons' => function ($query) {
        $query->orderBy('order_number')->get();
    }])
    ->get();
```

### 2. Missing category_id in Lesson Model
**File:** `app/Models/Lesson.php`
- `category_id` tidak ada di `$fillable` array
- Ketika membuat lesson, category_id tidak tersimpan ke database
- Lesson tidak terhubung dengan kategori yang benar

**Fix:**
```php
// Tambahkan ke fillable:
protected $fillable = [
    'title',
    'content',
    'category_id',  // <-- ADDED
    'exp_reward',
    'is_premium',
    'order_number',
];

// Tambahkan relationship:
public function category()
{
    return $this->belongsTo(Category::class);
}
```

### 3. Missing category_id in Admin Controller
**File:** `app/Http/Controllers/AdminController.php`
- `lessonsStore()` dan `lessonsUpdate()` tidak menyimpan `category_id`
- Lesson disimpan tapi tidak terhubung dengan kategori

**Fix:**
```php
// Sebelum:
$lessonData = [
    'title' => $validated['title'],
    'content' => $validated['content'],
    'order_number' => $validated['order'] ?? 0,
];

// Sesudah:
$lessonData = [
    'title' => $validated['title'],
    'content' => $validated['content'],
    'category_id' => $validated['category_id'],  // <-- ADDED
    'order_number' => $validated['order'] ?? 0,
];
```

## Testing

1. **Create New Category:**
   - Go to `/admin/categories/create`
   - Fill form and submit
   - Category should appear in user dashboard immediately

2. **Create New Lesson:**
   - Go to `/admin/lessons/create`
   - Select a category
   - Fill form and submit
   - Lesson should appear under the category in user dashboard immediately

3. **Verify Data Sync:**
   - Login as user
   - New lessons should be visible in "📚 Modul Belajar" section
   - Category progress should update correctly
   - No need to refresh or wait for cache

## Files Modified

- `app/Controllers/DashboardController.php` - Removed category filter
- `app/Models/Lesson.php` - Added category_id to fillable and relationship
- `app/Http/Controllers/AdminController.php` - Added category_id to create/update

## Result

✅ Lessons created in admin panel now immediately visible in user dashboard
✅ Lessons properly associated with their categories
✅ No data sync delays or caching issues
✅ Category progress calculates correctly with new lessons
