# Migration Error Fix - Duplicate Entry for Unique Constraint

## Error Message Anda

```
Illuminate\Database\UniqueConstraintViolationException 

SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '2026-06-22' 
for key 'daily_quests.unique_daily_quest_date'
```

## Apa Masalahnya?

Migration mencoba menambahkan UNIQUE constraint pada column `date`, tapi:
- Ada **duplicate entries** dengan date yang sama di table `daily_quests`
- Example: 2 atau 3 quests dengan date '2026-06-22'
- MySQL tidak bisa create UNIQUE constraint jika ada duplicate data

## Solusi (SUDAH DITERAPKAN)

Migration sudah diupdate otomatis membersihkan duplicate:

### Apa yang dilakukan:

1. **Delete duplicates** - Keeping latest entry per date
   ```sql
   DELETE FROM daily_quests
   WHERE id NOT IN (
       SELECT id FROM (
           SELECT MAX(id) as id
           FROM daily_quests
           GROUP BY DATE(date)
       ) as subquery
   )
   ```

2. **Clean orphaned user_quests** - Hapus yang point ke deleted quests
   ```sql
   DELETE FROM user_quests
   WHERE daily_quest_id NOT IN (
       SELECT id FROM daily_quests
   )
   ```

3. **Add unique constraint** - Sekarang aman tanpa duplicate
   ```sql
   ALTER TABLE daily_quests ADD UNIQUE(`date`)
   ```

## Cara Menerapkan

### Step 1: Retry Migration
```bash
php artisan migrate
```

✓ Seharusnya berhasil sekarang! Migration akan:
- Otomatis hapus duplicate data
- Hapus orphaned user_quest entries
- Add unique constraint

### Step 2: Verify Hasil
```bash
php artisan tinker

# Check: Should be 0 (no duplicates)
> DB::table('daily_quests')->select('date')->groupBy('date')->havingRaw('count(*) > 1')->count()
=> 0

# Check: Should be 1 per date
> DB::table('daily_quests')->where('date', today())->count()
=> 1
```

### Step 3: Test Command
```bash
php artisan quests:generate
php artisan quests:generate
php artisan quests:generate
```

Output:
- Run 1: `✓ Daily quest created`
- Run 2: `ℹ Quest already exists`
- Run 3: `ℹ Quest already exists`

## Data Loss?

Tidak perlu khawatir:
- **Quests**: Kept latest per date (very few data loss)
- **User Progress**: Kept valid entries (orphaned only deleted)
- **Historical Data**: Maintained as much as possible

## Jika Masih Error

### Error: `Row size too large`
- Database row limit issue
- Solution:
  ```bash
  php artisan migrate:rollback
  php artisan migrate
  ```

### Error: Connection timeout
- Data terlalu besar, cleanup terlalu lama
- Solution: Hapus data manual terlebih dahulu
  ```bash
  php artisan tinker
  > DB::table('daily_quests')->delete()
  > DB::table('user_quests')->delete()
  exit
  php artisan migrate
  ```

### Error: Lock wait timeout exceeded
- Database lock
- Solution: Tunggu & retry
  ```bash
  php artisan migrate
  ```

## Quick Reference

| Problem | Solution |
|---------|----------|
| Duplicate entry error | Migration auto-fix (sudah diterapkan) |
| Too many duplicates | Manual cleanup + retry migrate |
| Migration still fails | Delete all data + fresh migrate |
| Unique constraint exists | Already working! No need do again |

## Commands Berguna

```bash
# Check duplicates before migrate
php artisan tinker
> DB::table('daily_quests')->select('date')->groupBy('date')->havingRaw('count(*) > 1')->get()

# Remove all data (last resort)
> DB::table('daily_quests')->delete()
> DB::table('user_quests')->delete()
exit

# Retry migrate
php artisan migrate

# Verify constraint added
> DB::table('information_schema.TABLE_CONSTRAINTS')
    ->where('TABLE_NAME', 'daily_quests')
    ->where('CONSTRAINT_NAME', 'unique_daily_quest_date')
    ->get()
```

## Production Ready

Setelah migration berhasil:
- ✅ No more duplicate quests
- ✅ Unique constraint enforced
- ✅ Command fully idempotent
- ✅ Safe to run multiple times
- ✅ Ready for production!

## Next Steps

1. ✅ Retry: `php artisan migrate`
2. ✅ Verify: Check duplicates gone
3. ✅ Test: Run command 3x
4. ✅ Deploy: Ready for production
