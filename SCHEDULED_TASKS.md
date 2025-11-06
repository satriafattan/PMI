# 🕐 Setup Scheduled Tasks (Cron Jobs)

## Daftar Command yang Tersedia

### 1. Update Blood Units Expired

**Command**: `php artisan blood:update-expired`

**Fungsi**:

-   Update status unit darah yang sudah kadaluarsa menjadi 'expired'
-   Set jumlah stok batch yang expired menjadi 0
-   Clear cache stok

**Jadwal**: Setiap hari jam 00:01 (tengah malam)

### 2. Check Critical Stock

**Command**: `php artisan blood:check-critical`

**Fungsi**:

-   Cek stok darah yang kritis (< 20 unit)
-   Tampilkan list stok kritis di console
-   (Opsional) Kirim email notifikasi ke admin

**Jadwal**: Setiap hari jam 08:00 (pagi)

---

## 📝 Cara Setup

### A. Edit File `app/Console/Kernel.php`

Tambahkan di dalam method `schedule()`:

```php
protected function schedule(Schedule $schedule): void
{
    // Update expired blood units setiap hari jam 00:01
    $schedule->command('blood:update-expired')
        ->dailyAt('00:01')
        ->withoutOverlapping()
        ->runInBackground();

    // Check critical stock setiap hari jam 08:00
    $schedule->command('blood:check-critical')
        ->dailyAt('08:00')
        ->withoutOverlapping()
        ->runInBackground();

    // Clear cache setiap hari jam 03:00
    $schedule->command('cache:clear')
        ->dailyAt('03:00');

    // Backup database setiap minggu (Minggu jam 02:00)
    // $schedule->command('backup:run')
    //     ->weeklyOn(0, '02:00');
}
```

### B. Setup Cron Job di Server

#### Windows (Task Scheduler)

1. Buka Task Scheduler
2. Create New Task
3. Trigger: Daily at midnight
4. Action: Start a program
5. Program: `php`
6. Arguments: `e:\WEB PMI\artisan schedule:run`
7. Save

#### Linux/Ubuntu (Crontab)

Edit crontab:

```bash
crontab -e
```

Tambahkan line ini:

```bash
* * * * * cd /path/to/web-pmi && php artisan schedule:run >> /dev/null 2>&1
```

#### Shared Hosting (cPanel)

1. Login ke cPanel
2. Cari "Cron Jobs"
3. Add new cron job:
    - Minute: `*`
    - Hour: `*`
    - Day: `*`
    - Month: `*`
    - Weekday: `*`
    - Command: `/usr/local/bin/php /home/username/public_html/artisan schedule:run`

---

## 🧪 Testing Manual

Sebelum setup cron, test dulu manual:

```bash
# Test update expired
php artisan blood:update-expired

# Test check critical
php artisan blood:check-critical

# Test scheduler (dry-run)
php artisan schedule:list
```

---

## 📊 Monitoring

Cek log hasil scheduled tasks di:

-   `storage/logs/laravel.log`

Untuk monitoring lebih detail, tambahkan logging di Kernel.php:

```php
protected function schedule(Schedule $schedule): void
{
    $schedule->command('blood:update-expired')
        ->dailyAt('00:01')
        ->onSuccess(function () {
            Log::info('Blood expired update completed successfully');
        })
        ->onFailure(function () {
            Log::error('Blood expired update failed');
        });
}
```

---

## ⚡ Tips Optimasi

1. **Gunakan Queue untuk Task Berat**

    ```php
    $schedule->command('heavy:task')->dailyAt('02:00')->runInBackground();
    ```

2. **Prevent Overlap**

    ```php
    $schedule->command('task')->withoutOverlapping();
    ```

3. **Send Output to Log**

    ```php
    $schedule->command('task')
        ->dailyAt('01:00')
        ->appendOutputTo(storage_path('logs/scheduler.log'));
    ```

4. **Email on Failure**
    ```php
    $schedule->command('task')
        ->emailOutputOnFailure('admin@example.com');
    ```

---

## 🔧 Troubleshooting

### Cron tidak jalan?

1. Cek apakah PHP CLI path benar:

    ```bash
    which php
    ```

2. Cek permission artisan file:

    ```bash
    chmod +x artisan
    ```

3. Test manual schedule run:

    ```bash
    php artisan schedule:run
    ```

4. Cek log cron:

    ```bash
    # Linux
    tail -f /var/log/syslog | grep CRON

    # Windows
    Check Task Scheduler History
    ```

### Task berjalan tapi tidak ada efek?

1. Cek error log Laravel
2. Test command manual dulu
3. Pastikan database connection OK
4. Cek timezone di `.env` dan `config/app.php`

---

**Note**: Pastikan timezone di `config/app.php` sesuai dengan lokasi Anda (Asia/Jakarta)
