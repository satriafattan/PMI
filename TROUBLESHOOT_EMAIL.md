# 📧 Troubleshooting Email Verifikasi Pemesanan

## ✅ Status Saat Ini

Berdasarkan testing:

-   ✅ **SMTP Configuration**: Benar (smtp.gmail.com:587)
-   ✅ **Credentials**: Valid (jaflahx@gmail.com)
-   ✅ **Email Sending**: Berhasil dari server
-   ✅ **Log**: Menunjukkan "Email verifikasi berhasil dikirim"

## ⚠️ Kemungkinan Penyebab Email Tidak Sampai

### 1. **Email Masuk ke Folder SPAM** (Paling Sering)

Gmail sering menandai email otomatis sebagai spam, terutama jika:

-   Email baru pertama kali dikirim
-   Menggunakan App Password Gmail
-   Ada attachment PDF

**Solusi**:

```
✅ Cek folder SPAM/Junk di Gmail
✅ Tandai email sebagai "Not Spam"
✅ Pindahkan ke Inbox
✅ Email berikutnya akan masuk ke Inbox
```

### 2. **Gmail Block/Delay** (Jarang)

Gmail kadang delay email 5-15 menit untuk scanning

**Solusi**:

```
⏱️ Tunggu 5-15 menit
🔄 Refresh inbox
```

### 3. **MAIL_MAILER Not Set di .env**

Jika `MAIL_MAILER` tidak di-set, default ke 'log' (tidak kirim email asli)

**Cek**:

```bash
# Di file .env, pastikan ada:
MAIL_MAILER=smtp
```

**Fix**:

```bash
# Edit .env, tambahkan:
MAIL_MAILER=smtp

# Lalu clear config:
php artisan config:clear
```

## 🔧 Cara Testing Manual

### Test 1: Kirim Email Sederhana

```bash
php test-email.php
```

Output yang benar:

```
✅ Email berhasil dikirim!
Silakan cek inbox atau folder spam: email@example.com
```

### Test 2: Cek Log Laravel

```bash
# Windows PowerShell:
Get-Content storage\logs\laravel.log -Tail 20

# Cari baris:
[YYYY-MM-DD HH:MM:SS] local.INFO: Email verifikasi berhasil dikirim
```

### Test 3: Test dari Browser

1. Buat pemesanan baru
2. Klik Verifikasi → Approved/Rejected
3. Lihat pesan success:
    - ✅ Sukses: "Email notifikasi telah dikirim ke xxx@gmail.com"
    - ❌ Gagal: "Email notifikasi gagal dikirim: [error detail]"

## 📝 Checklist Email Configuration

Di file `.env`:

```env
# ✅ Pastikan semua ini sudah benar:
MAIL_MAILER=smtp                          # ← HARUS ADA!
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=jaflahx@gmail.com
MAIL_PASSWORD=faqmttjrtyrqrqsm            # App Password, bukan password biasa
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=jaflahx@gmail.com
MAIL_FROM_NAME="Unit Donor Darah PMI Provinsi Lampung"
```

## 🎯 Solusi Cepat

### Jika Email Tidak Sampai:

1. **Cek Folder SPAM terlebih dahulu!** (90% kasus)

    ```
    Gmail → More (sidebar) → Spam
    ```

2. **Clear config cache**

    ```bash
    php artisan config:clear
    ```

3. **Test kirim manual**

    ```bash
    php test-email.php
    ```

4. **Cek log error**

    ```bash
    Get-Content storage\logs\laravel.log -Tail 50
    ```

5. **Whitelist sender di Gmail**
    - Buka email dari jaflahx@gmail.com (di spam)
    - Klik "Not Spam"
    - Add jaflahx@gmail.com ke Contacts

## 📊 Log Email di Controller

Controller sekarang mencatat detail error:

```php
// Log sukses:
[INFO] Email verifikasi berhasil dikirim
  - pemesanan_id: 11
  - email: user@example.com
  - status: approved
  - mail_from: jaflahx@gmail.com

// Log error (jika ada):
[ERROR] SMTP Transport Error
  - error: Connection refused
  - smtp_host: smtp.gmail.com
  - smtp_port: 587
```

## 🚀 Tips Anti-SPAM

Untuk mengurangi kemungkinan masuk spam:

1. **Whitelist Email Sender**

    - Add `jaflahx@gmail.com` ke Gmail Contacts
    - Tandai email pertama sebagai "Not Spam"

2. **Verify Gmail App Password**

    - Pastikan App Password masih valid
    - Generate ulang jika perlu: https://myaccount.google.com/apppasswords

3. **Check Gmail Security**
    - Pastikan "Less secure app access" disabled (pakai App Password)
    - 2FA harus aktif untuk bisa generate App Password

## 📞 Support

Jika masih tidak berhasil setelah:

1. ✅ Cek folder SPAM
2. ✅ Clear config cache
3. ✅ Test manual berhasil
4. ❌ Email tetap tidak sampai

Kemungkinan:

-   Gmail block temporary (tunggu 1-2 jam)
-   Quota email terlampaui (max 500/day untuk Gmail free)
-   Email recipient invalid

---

## 🔍 Quick Debug Commands

```bash
# Clear cache
php artisan config:clear

# Test email
php test-email.php

# Cek log
Get-Content storage\logs\laravel.log -Tail 20 | Select-String "Email"

# Cek config
php artisan tinker
>>> config('mail.default')
=> "smtp"  # ← Harus "smtp", bukan "log"
>>> config('mail.from.address')
=> "jaflahx@gmail.com"
```
