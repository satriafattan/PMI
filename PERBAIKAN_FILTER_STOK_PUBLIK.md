# ✅ PERBAIKAN: Filter Stok Kadaluwarsa untuk User Publik

## 🐛 Bug yang Diperbaiki

**Masalah:**

-   User publik bisa melihat stok darah yang sudah kadaluwarsa
-   Homepage dan halaman stok menampilkan semua data tanpa filter expired
-   Data tidak akurat karena termasuk stok yang tidak bisa digunakan

**Dampak:**

-   ❌ User melihat jumlah stok yang lebih besar dari yang sebenarnya
-   ❌ Informasi menyesatkan (stok terlihat banyak padahal sebagian expired)
-   ❌ User bisa membuat keputusan berdasarkan data yang salah

---

## ✅ Solusi yang Diterapkan

### **File yang Diperbaiki:**

#### **1. WelcomeController.php** (Homepage)

**Sebelum:**

```php
$stok = StokDarah::where('produk', 'PRC')
    ->select(...)
    ->first(); // ❌ Ambil semua data (termasuk expired)
```

**Sesudah:**

```php
$stok = StokDarah::where('produk', 'PRC')
    ->whereDate('tgl_kadaluarsa', '>=', now()->toDateString()) // ✅ Filter expired
    ->where('jumlah', '>', 0) // ✅ Hanya yang ada stoknya
    ->select(...)
    ->first();
```

---

#### **2. StokController.php** (Halaman Stok Publik)

**Method `__invoke()` - Sebelum:**

```php
$stokAll = StokDarah::query()->get(); // ❌ Ambil semua
```

**Sesudah:**

```php
$stokAll = StokDarah::query()
    ->whereDate('tgl_kadaluarsa', '>=', now()->toDateString()) // ✅ Filter expired
    ->where('jumlah', '>', 0) // ✅ Hanya yang ada stoknya
    ->get();
```

**Method `getStokGolongan()` - Sebelum:**

```php
$stok = StokDarah::where('produk', 'PRC')
    ->select(...) // ❌ Ambil semua
    ->first();
```

**Sesudah:**

```php
$stok = StokDarah::where('produk', 'PRC')
    ->whereDate('tgl_kadaluarsa', '>=', now()->toDateString()) // ✅ Filter expired
    ->where('jumlah', '>', 0) // ✅ Hanya yang ada stoknya
    ->select(...)
    ->first();
```

---

## 🧪 Cara Testing

### **Test 1: Persiapan Data (Setup)**

#### **Buat data stok dengan beberapa yang expired:**

```sql
-- Stok VALID (belum kadaluwarsa)
INSERT INTO stok_darah (produk, gol_darah, rhesus, jumlah, tgl_masuk, tgl_kadaluarsa, created_at, updated_at)
VALUES
('PRC', 'A', 'Rh+', 50, '2025-11-17', '2025-12-17', NOW(), NOW()),
('PRC', 'B', 'Rh+', 30, '2025-11-17', '2025-12-17', NOW(), NOW()),
('PRC', 'O', 'Rh+', 40, '2025-11-17', '2025-12-17', NOW(), NOW()),
('PRC', 'AB', 'Rh+', 20, '2025-11-17', '2025-12-17', NOW(), NOW());

-- Stok EXPIRED (sudah kadaluwarsa)
INSERT INTO stok_darah (produk, gol_darah, rhesus, jumlah, tgl_masuk, tgl_kadaluarsa, created_at, updated_at)
VALUES
('PRC', 'A', 'Rh+', 100, '2025-11-01', '2025-11-15', NOW(), NOW()),
('PRC', 'B', 'Rh+', 80, '2025-11-01', '2025-11-14', NOW(), NOW()),
('PRC', 'O', 'Rh+', 90, '2025-11-01', '2025-11-13', NOW(), NOW());
```

---

### **Test 2: Verifikasi Homepage**

#### **Langkah:**

1. Buka browser (non-admin/publik)
2. Akses homepage: `http://localhost/`
3. Scroll ke section **"Stok PRC"**

#### **Yang Harus Terlihat:**

```
Golongan A: 50 kantong   ✅ (bukan 150)
Golongan B: 30 kantong   ✅ (bukan 110)
Golongan O: 40 kantong   ✅ (bukan 130)
Golongan AB: 20 kantong  ✅
```

#### **Yang TIDAK Boleh Terlihat:**

```
❌ Golongan A: 150 kantong (50 valid + 100 expired)
❌ Golongan B: 110 kantong (30 valid + 80 expired)
❌ Golongan O: 130 kantong (40 valid + 90 expired)
```

---

### **Test 3: Verifikasi Halaman Stok**

#### **Langkah:**

1. Buka halaman stok publik: `http://localhost/stok`
2. Periksa kartu per golongan darah
3. Periksa tabel detail per produk

#### **Kartu Golongan - Yang Harus Terlihat:**

```
┌─────────────────────┐
│ Golongan A          │
│ 50 Kantong     ✅   │
│ Status: Aman        │
└─────────────────────┘

┌─────────────────────┐
│ Golongan B          │
│ 30 Kantong     ✅   │
│ Status: Kritis      │
└─────────────────────┘
```

#### **Tabel Detail - Yang Harus Terlihat:**

```
Produk | A  | B  | O  | AB | Total
-------|----|----|----|----|------
PRC    | 50 | 30 | 40 | 20 | 140  ✅
WB     | .. | .. | .. | .. | ..
```

**TIDAK boleh:**

```
❌ PRC | 150 | 110 | 130 | 20 | 410  (termasuk expired)
```

---

### **Test 4: Verifikasi via Database**

#### **Query 1: Hitung stok yang seharusnya tampil (valid only)**

```sql
-- Stok VALID per golongan (yang user lihat)
SELECT gol_darah, SUM(jumlah) as total
FROM stok_darah
WHERE produk = 'PRC'
  AND tgl_kadaluarsa >= CURDATE()
  AND jumlah > 0
GROUP BY gol_darah;
```

**Expected Result:**

```
gol_darah | total
----------|------
A         | 50    ✅
B         | 30    ✅
O         | 40    ✅
AB        | 20    ✅
```

#### **Query 2: Hitung semua stok (termasuk expired)**

```sql
-- SEMUA stok (termasuk expired)
SELECT gol_darah, SUM(jumlah) as total
FROM stok_darah
WHERE produk = 'PRC'
GROUP BY gol_darah;
```

**Result (untuk perbandingan):**

```
gol_darah | total
----------|------
A         | 150   (50 valid + 100 expired) ❌ Tidak boleh tampil ke user
B         | 110   (30 valid + 80 expired)  ❌ Tidak boleh tampil ke user
O         | 130   (40 valid + 90 expired)  ❌ Tidak boleh tampil ke user
AB        | 20    (20 valid)                ✅
```

---

### **Test 5: Test API Endpoint**

#### **Request:**

```bash
curl http://localhost/api/stok-golongan
```

#### **Expected Response:**

```json
{
  "success": true,
  "stok": {
    "A": 50,   ✅ (bukan 150)
    "AB": 20,  ✅
    "B": 30,   ✅ (bukan 110)
    "O": 40    ✅ (bukan 130)
  },
  "lastUpdated": "17 Nov 2025, 14:30 WIB"
}
```

---

### **Test 6: Test Setelah Command Update**

#### **Jalankan command update expired:**

```bash
php artisan blood:update-expired
```

#### **Verifikasi:**

1. Stok expired harus di-set jumlah = 0
2. Homepage dan stok publik otomatis update
3. Tidak ada stok expired yang tampil

#### **Query Verifikasi:**

```sql
-- Cek apakah stok expired sudah di-set 0
SELECT * FROM stok_darah
WHERE tgl_kadaluarsa < CURDATE()
AND jumlah > 0;
-- Harus kosong (0 rows)
```

---

## 📊 Perbandingan Before vs After

### **Before (Bug):**

```
Homepage Golongan A: 150 kantong ❌
├─ Valid (belum expired): 50
└─ Expired: 100 ← TIDAK BOLEH DIHITUNG!

User melihat: 150 (SALAH)
```

### **After (Fixed):**

```
Homepage Golongan A: 50 kantong ✅
├─ Valid (belum expired): 50
└─ Expired: 0 (di-filter otomatis)

User melihat: 50 (BENAR)
```

---

## ✅ Checklist Testing

Gunakan checklist ini untuk memastikan perbaikan berhasil:

### Homepage (/)

-   [ ] Stok A hanya hitung yang valid (tidak termasuk expired)
-   [ ] Stok B hanya hitung yang valid
-   [ ] Stok O hanya hitung yang valid
-   [ ] Stok AB hanya hitung yang valid
-   [ ] Total stok = sum dari yang valid saja
-   [ ] Tanggal update tampil dengan format WIB

### Halaman Stok (/stok)

-   [ ] Kartu per golongan hanya tampil stok valid
-   [ ] Tabel detail per produk hanya tampil stok valid
-   [ ] Status badge (Aman/Perhatian/Kritis) berdasarkan stok valid
-   [ ] Filter search bekerja dengan benar
-   [ ] Tidak ada data expired yang muncul

### API Endpoint (/api/stok-golongan)

-   [ ] Response JSON hanya berisi stok valid
-   [ ] Angka sesuai dengan database (query dengan filter expired)
-   [ ] lastUpdated format benar (WIB)

### Database Consistency

-   [ ] Query manual dengan filter expired sama dengan tampilan user
-   [ ] Stok expired jumlah = 0 setelah command update
-   [ ] Tidak ada stok available dengan tgl_kadaluarsa < today

---

## 🔍 Troubleshooting

### **Problem: User masih lihat stok yang besar (termasuk expired)**

**Solusi:**

```bash
# 1. Clear cache
php artisan cache:clear
php artisan view:clear

# 2. Update expired units
php artisan blood:update-expired

# 3. Hard refresh browser
# Tekan: Ctrl + Shift + R (Windows/Linux)
# Atau: Cmd + Shift + R (Mac)
```

### **Problem: Angka tidak sesuai dengan database**

**Verifikasi Query:**

```sql
-- Cek stok valid secara manual
SELECT gol_darah, SUM(jumlah) as total
FROM stok_darah
WHERE produk = 'PRC'
  AND tgl_kadaluarsa >= CURDATE()
  AND jumlah > 0
GROUP BY gol_darah;
```

**Bandingkan dengan tampilan user.**

### **Problem: Stok 0 semua**

**Kemungkinan Penyebab:**

1. Semua stok memang sudah expired
2. Command update belum jalan

**Solusi:**

```bash
# Cek apakah ada stok valid
SELECT COUNT(*) FROM stok_darah WHERE tgl_kadaluarsa >= CURDATE();

# Jika 0, tambah stok baru dengan tanggal kadaluwarsa di masa depan
```

---

## 📝 Summary

### **Perbaikan yang Dilakukan:**

1. ✅ **WelcomeController**: Filter expired di homepage
2. ✅ **StokController**: Filter expired di halaman stok publik
3. ✅ **API Endpoint**: Filter expired di API response
4. ✅ **Tambahan Filter**: `jumlah > 0` untuk skip stok kosong

### **Filter yang Diterapkan:**

```php
->whereDate('tgl_kadaluarsa', '>=', now()->toDateString())  // ✅ Belum kadaluwarsa
->where('jumlah', '>', 0)                                   // ✅ Masih ada stoknya
```

### **Benefit untuk User:**

1. ✅ **Data Akurat**: User hanya lihat stok yang benar-benar tersedia
2. ✅ **Tidak Menyesatkan**: Angka stok sesuai dengan yang bisa dipesan
3. ✅ **Realtime**: Auto-update setiap hari via command
4. ✅ **Konsisten**: Homepage, halaman stok, dan API menampilkan data yang sama

---

**Status: FIXED ✅**

User publik sekarang **HANYA** bisa melihat stok darah yang:

-   ✅ Belum kadaluwarsa (`tgl_kadaluarsa >= hari ini`)
-   ✅ Masih ada stoknya (`jumlah > 0`)
-   ✅ Data akurat dan realtime
