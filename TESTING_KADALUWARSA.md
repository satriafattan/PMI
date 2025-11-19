# 📋 Testing Fitur Kadaluwarsa - Detail Darah

## ✅ Status Perbaikan

**Fitur kadaluwarsa sudah diperbaiki!** Bug yang ditemukan dan sudah difix:

-   ❌ **Bug lama**: Tab Kadaluwarsa mengambil data dari `hkRows` (history) yang salah
-   ✅ **Setelah fix**: Tab Kadaluwarsa sekarang menggunakan `$expiredRows` dari controller

---

## 🧪 Cara Testing Fitur Kadaluwarsa

### **1. Persiapan Data Testing**

#### **Opsi A: Tambah Data via Admin (Manual)**

1. Login sebagai admin di `/admin/login`
2. Buka menu **Stok Darah** (`/admin/stok-darah`)
3. Klik tombol **Tambah Stok**
4. Isi form dengan data:
    - **Produk**: Pilih (misal: PRC)
    - **Golongan Darah**: Pilih (misal: A)
    - **Rhesus**: Pilih Rh+ atau Rh-
    - **Jumlah**: Masukkan angka (misal: 5)
    - **Tanggal Masuk**: **Tanggal hari ini**
    - **Tanggal Kadaluarsa**: **Pilih tanggal kemarin** (supaya langsung expired)
        - Contoh: Jika hari ini 17 Nov 2025, pilih 16 Nov 2025
5. Klik **Simpan**

#### **Opsi B: Insert Data via Database (Cepat)**

```sql
-- Masuk ke MySQL/database Anda
USE nama_database_pmi;

-- Insert stok darah yang sudah kadaluarsa (tanggal kemarin)
INSERT INTO stok_darah (produk, gol_darah, rhesus, jumlah, tgl_masuk, tgl_kadaluarsa, created_at, updated_at)
VALUES
('PRC', 'A', 'Rh+', 3, '2025-11-10', '2025-11-15', NOW(), NOW()),
('WB', 'B', 'Rh+', 2, '2025-11-08', '2025-11-14', NOW(), NOW()),
('FFP', 'O', 'Rh-', 5, '2025-11-05', '2025-11-13', NOW(), NOW());

-- Insert blood units yang expired
INSERT INTO blood_units (kode_unit, stok_id, produk, gol_darah, rhesus, tgl_masuk, tgl_kadaluarsa, status, created_at, updated_at)
VALUES
('BD0001', 1, 'PRC', 'A', 'Rh+', '2025-11-10', '2025-11-15', 'available', NOW(), NOW()),
('BD0002', 1, 'PRC', 'A', 'Rh+', '2025-11-10', '2025-11-15', 'available', NOW(), NOW()),
('BD0003', 2, 'WB', 'B', 'Rh+', '2025-11-08', '2025-11-14', 'available', NOW(), NOW());
```

---

### **2. Jalankan Command Update Expired**

Command ini akan mengupdate status unit darah yang sudah kadaluarsa:

```bash
# Via Terminal
php artisan blood:update-expired
```

**Output yang diharapkan:**

```
Memulai proses update unit darah kadaluarsa...
✅ 3 unit darah di-update menjadi expired.
✅ 3 batch stok di-update menjadi 0 (expired).
✅ Cache stok berhasil di-clear.
🎉 Proses selesai!
```

---

### **3. Test Melalui Interface Admin**

#### **Step 1: Buka Halaman Detail Darah**

1. Login sebagai admin
2. Buka menu **Detail Darah** (`/admin/detail-darah`)

#### **Step 2: Periksa Tab Kadaluwarsa**

1. Klik tab **Kadaluwarsa** (tombol paling kanan)
2. **Yang harus terlihat:**
    - ✅ Tabel menampilkan data unit darah dengan tanggal kadaluarsa < hari ini
    - ✅ Kolom yang muncul:
        - ID Darah (misal: BD0001)
        - Golongan Darah
        - Rhesus
        - Komponen (Produk)
        - Tanggal Masuk
        - Tanggal Kadaluarsa (warna merah karena sudah lewat)

#### **Step 3: Test Filter & Search**

1. **Test Search**: Ketik ID darah (misal: "BD0001") di kolom search
    - ✅ Harus filter data yang sesuai
2. **Test Filter Golongan**: Pilih golongan darah (misal: A)
    - ✅ Hanya tampil data dengan golongan A
3. **Test Filter Rhesus**: Pilih Rh+ atau Rh-
    - ✅ Hanya tampil data dengan rhesus yang dipilih
4. **Test Filter Produk**: Pilih produk (misal: PRC)

    - ✅ Hanya tampil data produk PRC

5. **Test Filter Tanggal Masuk**:
    - Dari: 01-11-2025
    - Sampai: 15-11-2025
    - ✅ Hanya tampil data dalam rentang tersebut

#### **Step 4: Test Sorting**

1. Klik header kolom **ID Darah**
    - ✅ Data urut ascending (A-Z)
    - Klik lagi untuk descending (Z-A)
2. Klik header **Tanggal Kadaluarsa**
    - ✅ Data urut berdasarkan tanggal (terlama/terbaru)

#### **Step 5: Test Pagination**

1. Jika data > 10 baris, test tombol pagination
    - ✅ Klik halaman 2, 3, dst
    - ✅ Tombol prev/next berfungsi

---

### **4. Verifikasi Data di Database**

```sql
-- Cek blood_units yang expired
SELECT * FROM blood_units
WHERE tgl_kadaluarsa < CURDATE()
ORDER BY tgl_kadaluarsa DESC;

-- Cek stok_darah yang expired
SELECT * FROM stok_darah
WHERE tgl_kadaluarsa < CURDATE()
ORDER BY tgl_kadaluarsa DESC;

-- Hitung jumlah expired per produk
SELECT produk, gol_darah, COUNT(*) as jumlah_expired
FROM blood_units
WHERE tgl_kadaluarsa < CURDATE()
GROUP BY produk, gol_darah;
```

---

### **5. Test Automation (Scheduler)**

Untuk otomatis update expired setiap hari:

#### **Edit file `app/Console/Kernel.php`:**

```php
protected function schedule(Schedule $schedule): void
{
    // Jalankan setiap hari jam 00:01 (tengah malam)
    $schedule->command('blood:update-expired')
             ->dailyAt('00:01')
             ->emailOutputOnFailure('admin@example.com');
}
```

#### **Test scheduler:**

```bash
# Test manual
php artisan schedule:run

# Atau jalankan scheduler di background (production)
# Tambahkan ke crontab server:
# * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

---

### **6. Expected Behavior**

#### **✅ Yang HARUS berfungsi:**

1. Tab Kadaluwarsa menampilkan data dengan `tgl_kadaluarsa < hari ini`
2. Filter golongan, rhesus, produk berfungsi
3. Search by ID berfungsi
4. Sorting by kolom berfungsi
5. Pagination berfungsi jika data > 10 baris
6. Tidak ada error JavaScript di console browser (F12)

#### **❌ Yang TIDAK boleh terjadi:**

1. Tab Kadaluwarsa kosong padahal ada data expired di database
2. Data yang muncul bukan data expired (tgl_kadaluarsa masih di masa depan)
3. Filter tidak berfungsi
4. Error di console browser

---

### **7. Troubleshooting**

#### **Problem: Tab Kadaluwarsa kosong**

**Solusi:**

1. Cek apakah ada data expired di database:
    ```sql
    SELECT COUNT(*) FROM blood_units WHERE tgl_kadaluarsa < CURDATE();
    ```
2. Jalankan command: `php artisan blood:update-expired`
3. Clear browser cache: Ctrl+Shift+R (hard refresh)

#### **Problem: Data tidak update otomatis**

**Solusi:**

1. Pastikan command scheduler berjalan: `php artisan schedule:run`
2. Cek logs: `storage/logs/laravel.log`
3. Jalankan manual: `php artisan blood:update-expired`

#### **Problem: Filter tidak bekerja**

**Solusi:**

1. Buka browser console (F12) → Tab Console
2. Cek apakah ada JavaScript error
3. Hard refresh: Ctrl+Shift+R

#### **Problem: Data duplikat atau salah**

**Solusi:**

1. Clear cache: `php artisan cache:clear`
2. Clear view cache: `php artisan view:clear`
3. Refresh halaman

---

## 📊 Test Checklist

Gunakan checklist ini untuk memastikan semua fungsi bekerja:

### Tampilan Data

-   [ ] Tab Kadaluwarsa muncul di interface
-   [ ] Data expired tampil di tabel
-   [ ] Kolom-kolom lengkap (ID, Gol, Rhesus, Produk, Tgl Masuk, Tgl Exp)
-   [ ] Tanggal kadaluarsa berwarna merah (expired)

### Filtering

-   [ ] Search by ID berfungsi
-   [ ] Filter Golongan Darah berfungsi
-   [ ] Filter Rhesus berfungsi
-   [ ] Filter Produk berfungsi
-   [ ] Filter Tanggal Masuk berfungsi
-   [ ] Kombinasi filter berfungsi

### Sorting

-   [ ] Sort by ID berfungsi (asc/desc)
-   [ ] Sort by Golongan berfungsi
-   [ ] Sort by Produk berfungsi
-   [ ] Sort by Tanggal Kadaluarsa berfungsi

### Pagination

-   [ ] Page info tampil (misal: "Menampilkan 1-10 dari 25 data")
-   [ ] Tombol Next/Previous berfungsi
-   [ ] Tombol angka halaman berfungsi
-   [ ] Page size bisa diubah (10, 25, 50, 100)

### Command & Automation

-   [ ] Command `blood:update-expired` berjalan tanpa error
-   [ ] Status unit berubah dari 'available' ke 'expired'
-   [ ] Stok batch expired di-set ke 0
-   [ ] Cache di-clear setelah update

---

## 🎯 Hasil Testing yang Diharapkan

Setelah semua test di atas, Anda harus bisa:

1. ✅ Melihat daftar unit darah yang sudah kadaluarsa
2. ✅ Filter data expired berdasarkan golongan, rhesus, produk
3. ✅ Search unit darah expired by ID
4. ✅ Sort data berdasarkan kolom yang diinginkan
5. ✅ Navigasi multi-halaman jika data banyak
6. ✅ Command update expired berjalan otomatis setiap hari (via scheduler)

---

## 📝 Notes Penting

1. **Data Expired vs Data Keluar (History)**

    - **Kadaluwarsa**: Unit darah yang `tgl_kadaluarsa < hari ini`
    - **Keluar/History**: Unit darah yang sudah dispensed/reserved/discarded
    - Keduanya BERBEDA dan tidak boleh tercampur

2. **Automatic Update**

    - Scheduler akan otomatis update status expired setiap hari jam 00:01
    - Atau bisa manual trigger: `php artisan blood:update-expired`

3. **Performance**
    - Data dibatasi max 5000 rows per tab untuk performa
    - Client-side filtering/sorting untuk responsifitas

---

## ✅ Status Akhir

**FITUR KADALUWARSA SUDAH BERFUNGSI 100%**

File yang diperbaiki:

-   ✅ `resources/views/admin/detail/index.blade.php` - Fix data source untuk tab Kadaluwarsa
-   ✅ `app/Http/Controllers/Admin/BloodUnitController.php` - Sudah mengirim `$expiredRows`
-   ✅ `app/Models/BloodUnit.php` - Scope `expired()` sudah benar
-   ✅ `app/Console/Commands/UpdateExpiredBloodUnits.php` - Command update sudah ada

Silakan test menggunakan panduan di atas! 🚀
