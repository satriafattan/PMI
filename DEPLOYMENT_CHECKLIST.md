# 🚀 DEPLOYMENT CHECKLIST - SIMPHONY (Sistem Informasi Pemesanan & Inventori)

## ✅ STATUS PEMERIKSAAN PRE-DEPLOYMENT

**Tanggal Pemeriksaan:** 26 November 2025  
**Status Keseluruhan:** ✅ SIAP DEPLOY (dengan catatan)

---

## 📋 CHECKLIST UTAMA

### 1. ✅ KONFIGURASI ENVIRONMENT

#### File .env (WAJIB DI-UPDATE UNTUK PRODUCTION)

```bash
# Update nilai berikut untuk production:

APP_NAME="SIMPHONY - PMI"
APP_ENV=production                    # ⚠️ PENTING: Ubah dari 'local' ke 'production'
APP_KEY=                              # ⚠️ WAJIB: Generate dengan 'php artisan key:generate'
APP_DEBUG=false                       # ⚠️ PENTING: Set ke 'false' untuk production
APP_URL=https://your-domain.com       # ⚠️ Update dengan domain production

# Database Production
DB_CONNECTION=mysql
DB_HOST=your-production-host          # ⚠️ Update
DB_PORT=3306
DB_DATABASE=your-production-db        # ⚠️ Update
DB_USERNAME=your-db-user              # ⚠️ Update
DB_PASSWORD=your-secure-password      # ⚠️ Update

# Mail Configuration (untuk forgot password & notifications)
MAIL_MAILER=smtp                      # ⚠️ Update dari 'log' ke 'smtp'
MAIL_HOST=smtp.gmail.com              # ⚠️ Update
MAIL_PORT=587                         # ⚠️ Update
MAIL_USERNAME=your-email@gmail.com    # ⚠️ Update
MAIL_PASSWORD=your-app-password       # ⚠️ Update
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@your-domain.com"
MAIL_FROM_NAME="${APP_NAME}"

# Session & Cache
SESSION_DRIVER=database               # ✅ Sudah benar
CACHE_STORE=database                  # ✅ Sudah benar
QUEUE_CONNECTION=database             # ✅ Sudah benar
```

---

### 2. ✅ KEAMANAN & SECURITY

#### ✅ Password Hashing

-   Menggunakan bcrypt (BCRYPT_ROUNDS=12)

#### ✅ CSRF Protection

-   Semua form memiliki @csrf token

#### ✅ Rate Limiting

-   Pemesanan publik: 3 requests per 10 menit
-   Event scheduling: 5 requests per 10 menit
-   Login (built-in Laravel throttling)

#### ⚠️ CATATAN KEAMANAN

1. **Pastikan SSL/HTTPS aktif** untuk production
2. **Update .htaccess** jika menggunakan Apache
3. **Disable directory listing** di web server
4. **Backup database** sebelum deployment

---

### 3. ✅ DATABASE & MIGRASI

#### File Migrasi (Semua OK)

-   ✅ users table
-   ✅ admins table
-   ✅ pemesanan_darah table
-   ✅ stok_darah table
-   ✅ blood_units table
-   ✅ event_schedules table
-   ✅ event_verifications table
-   ✅ verifikasi_pemesanan table
-   ✅ cache, jobs, sessions tables

#### Seeder (Optional untuk production)

-   ⚠️ Gunakan seeder hanya untuk data dummy/testing
-   ⚠️ Jangan jalankan seeder di production kecuali untuk data master

#### Command untuk Deployment:

```bash
# Di server production:
php artisan migrate --force
php artisan db:seed --class=AdminSeeder  # Hanya jika perlu create admin pertama
```

---

### 4. ✅ FITUR APLIKASI

#### ✅ Modul Public (Tanpa Login)

-   ✅ Landing page & Informasi
-   ✅ Form pemesanan darah
-   ✅ Konfirmasi pemesanan via kode
-   ✅ Jadwal event donor darah
-   ✅ Lihat stok darah publik

#### ✅ Modul Admin (Dengan Login)

-   ✅ Dashboard & statistik
-   ✅ Verifikasi pemesanan
-   ✅ Manajemen stok darah
-   ✅ Detail darah (Tersedia/Keluar/Kadaluwarsa)
-   ✅ Export Excel (Tersedia/Keluar/Kadaluwarsa)
-   ✅ Laporan pemesanan
-   ✅ Export laporan (Excel)
-   ✅ Verifikasi event
-   ✅ Riwayat pemesanan
-   ✅ Manajemen admin
-   ✅ Notifikasi real-time (polling)
-   ✅ Forgot password & reset password

---

### 5. ✅ VALIDASI & ERROR HANDLING

#### ✅ Form Validation

-   Semua input divalidasi di controller
-   Error messages ditampilkan dengan baik

#### ✅ Error Pages

-   404, 500, 403 handled by Laravel

#### ⚠️ Debug Mode

-   **WAJIB: Set APP_DEBUG=false** di production
-   Akan menampilkan error generic, bukan stack trace

---

### 6. ✅ ASSETS & FRONTEND

#### ✅ Vite Build

```bash
# Sebelum deploy, jalankan:
npm install
npm run build
```

#### ✅ File Assets

-   ✅ CSS compiled via Tailwind CSS
-   ✅ JavaScript bundle via Vite
-   ✅ SVG icons inline
-   ✅ Images di folder public/images

---

### 7. ✅ DEPENDENCIES

#### ✅ Composer Packages (Production)

```json
{
    "php": "^8.2",
    "laravel/framework": "^11.0",
    "barryvdh/laravel-dompdf": "^3.1",
    "maatwebsite/excel": "3.1.67"
}
```

#### ✅ NPM Packages

-   Tailwind CSS
-   Alpine.js
-   Vite

#### Command Installation:

```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

---

### 8. ✅ PERFORMANCE OPTIMIZATION

#### Optimasi Cache (Jalankan di production):

```bash
# Config cache
php artisan config:cache

# Route cache
php artisan route:cache

# View cache
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Clear old cache
php artisan cache:clear
```

---

### 9. ⚠️ KNOWN ISSUES & TODO

#### ✅ Fixed Issues

1. ✅ Bullet points di navbar dropdown - FIXED
2. ✅ Format tanggal terlalu panjang - FIXED (sekarang DD-MM-YYYY)
3. ✅ Export Excel untuk Detail Darah - IMPLEMENTED
4. ✅ Tanggal masuk/kadaluwarsa di Stok Darah - REMOVED (sesuai permintaan)

#### 📝 Minor TODO (Tidak Critical)

1. Email notification untuk stok kritis (ada TODO di CheckCriticalStock.php line 58)
    - Saat ini sistem sudah logging, tinggal implementasi email

#### ⚠️ Catatan

-   Tidak ada debug function (dd, dump, var_dump) di kode production
-   Tidak ada TODO/FIXME yang critical

---

### 10. ✅ FILE PERMISSIONS (Linux Server)

```bash
# Storage & Bootstrap cache permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Ownership (sesuaikan dengan web server user)
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

---

## 🚀 LANGKAH DEPLOYMENT

### A. Persiapan di Local

1. **Update .env untuk production**

    ```bash
    cp .env.example .env.production
    # Edit .env.production dengan konfigurasi production
    ```

2. **Build assets**

    ```bash
    npm install
    npm run build
    ```

3. **Test di local dengan APP_ENV=production**

    ```bash
    php artisan config:clear
    php artisan serve
    ```

4. **Commit & Push ke repository**
    ```bash
    git add .
    git commit -m "Ready for production deployment"
    git push origin master
    ```

---

### B. Deployment di Server

1. **Clone/Pull repository**

    ```bash
    cd /var/www/html
    git clone https://github.com/satriafattan/PMI.git
    # ATAU
    git pull origin master
    ```

2. **Install dependencies**

    ```bash
    composer install --optimize-autoloader --no-dev
    npm install
    npm run build
    ```

3. **Setup environment**

    ```bash
    cp .env.example .env
    php artisan key:generate
    # Edit .env dengan konfigurasi production
    ```

4. **Setup database**

    ```bash
    php artisan migrate --force
    php artisan db:seed --class=AdminSeeder  # Jika perlu
    ```

5. **Set permissions**

    ```bash
    chmod -R 775 storage bootstrap/cache
    chown -R www-data:www-data storage bootstrap/cache
    ```

6. **Cache optimization**

    ```bash
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    ```

7. **Restart services**
    ```bash
    sudo systemctl restart php8.2-fpm  # sesuaikan versi PHP
    sudo systemctl restart nginx       # atau apache2
    ```

---

### C. Post-Deployment Checklist

-   [ ] Test login admin
-   [ ] Test pemesanan publik
-   [ ] Test export Excel
-   [ ] Test forgot password email
-   [ ] Test notifikasi real-time
-   [ ] Test responsive mobile
-   [ ] Monitor error logs: `tail -f storage/logs/laravel.log`

---

## 📊 MONITORING

### Log Files

```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Nginx/Apache logs
tail -f /var/log/nginx/error.log
tail -f /var/log/apache2/error.log
```

### Database Backup (Recommended)

```bash
# Daily backup script
mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql
```

---

## 🔒 SECURITY RECOMMENDATIONS

1. ✅ **HTTPS/SSL** - Wajib aktif
2. ✅ **Firewall** - Hanya port 80, 443, 22 (SSH) yang terbuka
3. ✅ **Database** - Tidak dapat diakses dari luar
4. ✅ **Backup** - Setup automatic backup harian
5. ✅ **Updates** - Regular security updates untuk server & dependencies
6. ✅ **Monitoring** - Setup uptime monitoring

---

## 📞 SUPPORT & MAINTENANCE

### Update Application

```bash
git pull origin master
composer install --optimize-autoloader --no-dev
npm install && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo systemctl restart php8.2-fpm
```

### Clear All Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## ✅ KESIMPULAN

**Status:** SIAP UNTUK DEPLOYMENT ✅

**Catatan Penting:**

1. ⚠️ Update semua konfigurasi di .env untuk production
2. ⚠️ Set APP_DEBUG=false
3. ⚠️ Setup HTTPS/SSL
4. ⚠️ Test semua fitur setelah deployment
5. ⚠️ Setup backup database otomatis

**Tidak Ada Error Critical** - Sistem dapat langsung dideploy setelah konfigurasi environment disesuaikan.

---

**Last Updated:** 26 November 2025  
**Version:** 1.0.0  
**Developer:** Satria Fattan
