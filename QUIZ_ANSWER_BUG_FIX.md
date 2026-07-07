# Quiz Answer Comparison Bug Fix

## Problem
User menjawab quiz dengan opsi yang benar, tetapi sistem menandai jawaban sebagai salah. Padahal di admin panel, jawaban yang sama sudah ditandai sebagai jawaban yang benar.

### Contoh Kasus:
- Admin membuat quiz dengan opsi C sebagai jawaban benar (correct_answer = 2)
- User memilih opsi C (mengirim answer = "2")
- Sistem memberikan pesan "❌ Jawaban salah"
- Padahal menampilkan jawaban yang benar adalah: "Para elit agar tidak banyak orang yang semakin kaya" (opsi C)

## Root Cause
Type mismatch dalam perbandingan jawaban:

1. **Di Admin Panel:**
   - Form mengirim correct_answer sebagai string: "2"
   - AdminController cast ke integer: `(int)"2"` = `2`
   - Database menyimpan sebagai integer: `2`

2. **Di User Side:**
   - JavaScript mengirim answer sebagai string: `"2"`
   - QuizController perbandingan: `(2 === "2")`
   - PHP strict comparison: **FALSE** ❌

3. **Masalah:**
   - `2 === "2"` adalah FALSE (strict comparison di PHP)
   - Seharusnya `2 === 2` (TRUE)

## Solution
Menambahkan integer casting pada kedua sisi perbandingan dan di model:

### 1. QuizController - submitAnswer()
```php
// BEFORE
$answer = $request->input('answer');
$isCorrect = ($quiz->correct_answer === $answer);

// AFTER
$answer = (int)$request->input('answer');
$isCorrect = ((int)$quiz->correct_answer === $answer);
```

### 2. Quiz Model - Add Type Casting
```php
protected $casts = [
    'options' => 'array',
    'correct_answer' => 'integer', // NEW
    'points' => 'integer',          // NEW
];
```

### 3. AdminController - Already Had Casting
```php
// quizzesStore() & quizzesUpdate()
$validated['correct_answer'] = (int)$validated['correct_answer'];
```

## Files Changed
- `app/Http/Controllers/QuizController.php`
- `app/Models/Quiz.php`

## Testing
1. Go to `/admin/quizzes/create`
2. Create quiz dengan correct answer = C (index 2)
3. Login sebagai user
4. Go to quiz
5. Select opsi C
6. Submit
7. Harusnya muncul: "✅ Jawaban benar!"

## Impact
- Semua quiz answers sekarang dievaluasi dengan benar
- Type-safe comparison mencegah bug serupa di masa depan
- Existing answers tidak affected (evaluation only changed)

## Database Migration (Optional)
Jika ingin memastikan semua existing data di database adalah integer:
```sql
UPDATE quizzes SET correct_answer = CAST(correct_answer AS UNSIGNED) WHERE correct_answer IS NOT NULL;
```
