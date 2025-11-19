# 🛡️ Dokumentasi Pencegahan Pemesanan Darah Kadaluwarsa

## ✅ Status: SISTEM SUDAH AMAN

Sistem **SIMPHONY** sudah dilengkapi dengan **multiple layer protection** untuk mencegah pemesanan darah yang sudah kadaluwarsa.

---

## 🔒 Layer Proteksi

### **Layer 1: Model Scope (Database Level)**
**File:** `app/Models/BloodUnit.php`

```php
/** Unit yang siap pakai & belum kedaluwarsa */
public function scopeAvailable(Builder $q): Builder
{
    return $q->where('status', 'available')
            ->whereDate('tgl_kadaluarsa', '>=', now()->toDateString());
}

/** Unit yang sudah kedaluwarsa (exp < hari ini) */
public function scopeExpired(Builder $q): Builder
{
    return $q->whereDate('tgl_kadaluarsa', '<', now()->toDateString());
}
```

**Cara Kerja:**
- Scope `available()` otomatis filter unit darah yang:
  - ✅ Status = 'available' 
  - ✅ Tanggal kadaluwarsa ≥ hari ini
- Scope `expired()` untuk menampilkan unit yang sudah kadaluwarsa

---

### **Layer 2: Controller Validation (Business Logic)**
**File:** `app/Http/Controllers/Admin/VerifikasiPemesananController.php`

```php
private function allocateUnitsAndSyncStock(PemesananDarah $pemesanan): array
{
    // Cari unit available yang cocok (FEFO by tgl_kadaluarsa)
    $units = BloodUnit::query()
        ->where('produk', $pemesanan->produk)
        ->where('gol_darah', $pemesanan->gol_darah)
        ->when($pemesanan->rhesus, fn($q) => $q->where('rhesus', $pemesanan->rhesus))
        ->where('status', 'available')
        ->whereDate('tgl_kadaluarsa', '>=', now()->toDateString()) // ✅ VALIDASI KADALUWARSA
        ->orderBy('tgl_kadaluarsa') // FEFO: First Expired, First Out
        ->lockForUpdate() // Cegah race condition
        ->limit($needed)
        ->get();

    // Validasi jumlah stok
    if ($units->count() < $needed) {
        throw ValidationException::withMessages([
            'status' => "Stok unit tidak mencukupi. Dibutuhkan {$needed}, tersedia {$units->count()}.",
        ]);
    }
    
    // ... alokasi unit
}
```

**Cara Kerja:**
- Saat admin approve pemesanan, sistem hanya mengambil unit dengan `tgl_kadaluarsa >= hari ini`
- Jika tidak ada unit yang valid, akan muncul error
- Menggunakan FEFO (First Expired, First Out) untuk prioritaskan yang mendekati kadaluwarsa

---

### **Layer 3: Dashboard Filter (Display Level)**
**File:** `app/Http/Controllers/DashboardController.php`

```php
// Hitung total stok available (belum expired)
$totalStok = BloodUnit::where('status', 'available')
    ->whereDate('tgl_kadaluarsa', '>=', now()->toDateString()) // ✅ Hanya hitung stok valid
    ->count();

// Stok per golongan (hanya yang belum expired)
$stokPerGolongan = BloodUnit::where('status', 'available')
    ->whereDate('tgl_kadaluarsa', '>=', now()->toDateString())
    ->select('gol_darah', DB::raw('count(*) as total'))
    ->groupBy('gol_darah')
    ->get();
```

**Cara Kerja:**
- Dashboard hanya menampilkan stok yang valid (belum kadaluwarsa)
- Grafik dan statistik tidak termasuk unit expired
- Admin mendapat data akurat tentang stok yang bisa dipesan

---

### **Layer 4: Automatic Status Update (Cron Job)**
**File:** `app/Console/Commands/UpdateExpiredBloodUnits.php`

```php
public function handle()
{
    DB::transaction(function () {
        // Update blood units yang sudah expired
        $updatedUnits = BloodUnit::where('status', 'available')
            ->whereDate('tgl_kadaluarsa', '<', now()->toDateString())
            ->update(['status' => 'expired']); // ✅ Auto-update status

        // Sinkronkan stok_darah (set jumlah = 0 untuk batch yang expired)
        $expiredBatches = StokDarah::whereDate('tgl_kadaluarsa', '<', now()->toDateString())
            ->where('jumlah', '>', 0)
            ->get();

        foreach ($expiredBatches as $batch) {
            $batch->update(['jumlah' => 0]); // ✅ Reset stok expired ke 0
        }

        // Clear cache stok
        \App\Services\StokCacheService::clearCache();
    });
}
```

**Cara Kerja:**
- Command berjalan otomatis setiap hari jam 00:01 (via scheduler)
- Mengubah status unit darah dari 'available' → 'expired'
- Menset jumlah batch stok expired ke 0
- Clear cache untuk update tampilan realtime

**Setup Scheduler:**
```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule): void
{
    $schedule->command('blood:update-expired')
             ->dailyAt('00:01');
}
```

---

## 🧪 Skenario Testing

### **Skenario 1: Pemesanan Darah Valid (Belum Kadaluwarsa)**

**Setup:**
```sql
INSERT INTO blood_units (kode_unit, produk, gol_darah, rhesus, tgl_masuk, tgl_kadaluarsa, status)
VALUES ('BD001', 'PRC', 'A', 'Rh+', '2025-11-17', '2025-12-17', 'available');
```

**Test:**
1. User pesan PRC golongan A Rh+ (1 kantong)
2. Admin approve pemesanan

**Expected Result:**
- ✅ Pemesanan berhasil
- ✅ Unit BD001 di-alokasikan
- ✅ Status berubah dari 'available' → 'dispensed'
- ✅ Email notifikasi terkirim

---

### **Skenario 2: Pemesanan Darah Kadaluwarsa (HARUS DITOLAK)**

**Setup:**
```sql
-- Unit yang sudah kadaluwarsa (tanggal kemarin)
INSERT INTO blood_units (kode_unit, produk, gol_darah, rhesus, tgl_masuk, tgl_kadaluarsa, status)
VALUES ('BD002', 'PRC', 'A', 'Rh+', '2025-11-01', '2025-11-16', 'available');

-- Jalankan command update
php artisan blood:update-expired
```

**Test:**
1. User pesan PRC golongan A Rh+ (1 kantong)
2. Admin approve pemesanan

**Expected Result:**
- ❌ Pemesanan GAGAL
- ❌ Error: "Stok unit tidak mencukupi. Dibutuhkan 1, tersedia 0."
- ❌ Unit BD002 TIDAK di-alokasikan (karena expired)
- ✅ Stok available = 0 (karena semua expired)

---

### **Skenario 3: Mixed (Ada yang Valid, Ada yang Kadaluwarsa)**

**Setup:**
```sql
-- Unit valid
INSERT INTO blood_units (kode_unit, produk, gol_darah, rhesus, tgl_masuk, tgl_kadaluarsa, status)
VALUES ('BD003', 'PRC', 'A', 'Rh+', '2025-11-17', '2025-12-17', 'available');

-- Unit expired
INSERT INTO blood_units (kode_unit, produk, gol_darah, rhesus, tgl_masuk, tgl_kadaluarsa, status)
VALUES ('BD004', 'PRC', 'A', 'Rh+', '2025-11-01', '2025-11-16', 'expired');
```

**Test:**
1. User pesan PRC golongan A Rh+ (2 kantong)
2. Admin approve pemesanan

**Expected Result:**
- ❌ Pemesanan GAGAL
- ❌ Error: "Stok unit tidak mencukupi. Dibutuhkan 2, tersedia 1."
- ✅ Sistem hanya hitung BD003 (valid)
- ✅ BD004 TIDAK masuk perhitungan (expired)

---

## 📊 Flow Diagram Pemesanan

```
[User Submit Pemesanan]
        ↓
[Pemesanan Status = 'pending']
        ↓
[Admin Review & Approve]
        ↓
[Controller: allocateUnitsAndSyncStock()]
        ↓
[Query: BloodUnit::available() + whereDate(tgl_kadaluarsa >= today)]
        ↓
    ┌───────────────────┬───────────────────┐
    ↓                   ↓                   ↓
[Ada Stok Valid]   [Tidak Ada Stok]   [Stok < Diminta]
    ↓                   ↓                   ↓
✅ Alokasi Unit      ❌ Error           ❌ Error
    ↓
[Update Status: dispensed]
    ↓
[Update Stok Batch: jumlah - 1]
    ↓
[Kirim Email ke Pemesan]
    ↓
✅ Pemesanan Selesai
```

---

## 🔍 Cara Verifikasi Sistem Aman

### **1. Cek Model Scope**
```bash
php artisan tinker
```

```php
// Hitung unit available (harus hanya yang belum expired)
BloodUnit::available()->count();

// Hitung unit expired
BloodUnit::expired()->count();

// Cek unit dengan tanggal kadaluwarsa kemarin (harus tidak masuk available)
BloodUnit::available()
    ->whereDate('tgl_kadaluarsa', '<', now()->toDateString())
    ->count(); // Harus = 0
```

### **2. Cek Database**
```sql
-- Unit available tapi sudah kadaluwarsa (harus kosong/0)
SELECT * FROM blood_units 
WHERE status = 'available' 
AND tgl_kadaluarsa < CURDATE();
-- Result: 0 rows (kalau ada = BUG!)

-- Unit expired dengan status salah (harus kosong/0)
SELECT * FROM blood_units 
WHERE status = 'available' 
AND tgl_kadaluarsa < CURDATE();
-- Result: 0 rows (kalau ada = perlu jalankan command update)
```

### **3. Test Manual via Interface**

**Langkah:**
1. Login admin → `/admin/login`
2. Buka **Detail Darah** → `/admin/detail-darah`
3. Tab **Tersedia** → cek kolom Tanggal Kadaluarsa
4. ✅ Semua tanggal harus ≥ hari ini
5. ❌ Jika ada tanggal < hari ini = BUG!

**Langkah 2:**
1. Buat pemesanan dengan golongan yang HANYA ada unit expired
2. Admin approve
3. ✅ Harus muncul error "Stok tidak mencukupi"

---

## ⚙️ Maintenance & Monitoring

### **Daily Cron Job**
```bash
# Pastikan scheduler berjalan (di server production)
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

### **Manual Trigger**
```bash
# Jalankan manual jika perlu
php artisan blood:update-expired
```

### **Monitor Logs**
```bash
# Cek apakah command berjalan
tail -f storage/logs/laravel.log | grep "blood:update-expired"
```

### **Database Check (Weekly)**
```sql
-- Cek unit yang statusnya tidak konsisten
SELECT COUNT(*) as total_inconsistent
FROM blood_units 
WHERE status = 'available' 
AND tgl_kadaluarsa < CURDATE();

-- Jika > 0, jalankan: php artisan blood:update-expired
```

---

## 🚨 Troubleshooting

### **Problem: Unit expired masih bisa dipesan**
**Solusi:**
```bash
# 1. Jalankan command update
php artisan blood:update-expired

# 2. Cek database
SELECT * FROM blood_units WHERE status = 'available' AND tgl_kadaluarsa < CURDATE();

# 3. Clear cache
php artisan cache:clear
php artisan view:clear
```

### **Problem: Dashboard masih tampilkan stok expired**
**Solusi:**
```bash
# Clear cache stok
php artisan cache:clear

# Atau via code
\App\Services\StokCacheService::clearCache();
```

### **Problem: Command tidak berjalan otomatis**
**Solusi:**
```bash
# Cek scheduler
php artisan schedule:list

# Test run manual
php artisan schedule:run

# Pastikan crontab sudah disetup (server production)
crontab -e
# Tambahkan: * * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

---

## ✅ Kesimpulan

### **Sistem Sudah Aman Karena:**

1. ✅ **Model Scope** otomatis filter expired di level database
2. ✅ **Controller Validation** double-check sebelum alokasi
3. ✅ **Dashboard** hanya tampilkan stok valid
4. ✅ **Automatic Update** via cron job setiap hari
5. ✅ **FEFO Logic** prioritaskan unit yang mendekati kadaluwarsa
6. ✅ **Lock Mechanism** cegah race condition
7. ✅ **Error Handling** beri feedback jelas ke admin

### **Yang TIDAK Mungkin Terjadi:**

❌ User pesan darah kadaluwarsa → **DICEGAH** oleh scope + validation
❌ Admin approve darah expired → **DICEGAH** oleh whereDate filter
❌ Dashboard tampilkan stok expired → **DICEGAH** oleh scope available()
❌ Unit expired ter-alokasi → **DICEGAH** oleh multiple layer protection

### **Best Practice:**

1. ✅ Jalankan `blood:update-expired` setiap hari (via scheduler)
2. ✅ Monitor database weekly untuk konsistensi
3. ✅ Clear cache setelah update manual
4. ✅ Test dengan data dummy sebelum production

---

**Sistem SIMPHONY sudah dilengkapi dengan proteksi berlapis untuk mencegah pemesanan darah kadaluwarsa!** 🛡️
