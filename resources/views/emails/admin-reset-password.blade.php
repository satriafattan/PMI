<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, initial-scale=1.0">
  <title>Reset Password Admin</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      line-height: 1.6;
      color: #333;
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
    }

    .container {
      background-color: #f9f9f9;
      border-radius: 8px;
      padding: 30px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .header {
      text-align: center;
      margin-bottom: 30px;
    }

    .logo {
      max-width: 120px;
      margin-bottom: 20px;
    }

    h1 {
      color: #dc2626;
      font-size: 24px;
      margin-bottom: 10px;
    }

    .content {
      background-color: white;
      padding: 25px;
      border-radius: 6px;
      margin-bottom: 20px;
    }

    .button {
      display: inline-block;
      padding: 14px 32px;
      background-color: #dc2626;
      color: white !important;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
      text-align: center;
      margin: 20px 0;
    }

    .button:hover {
      background-color: #b91c1c;
    }

    .info-box {
      background-color: #fef2f2;
      border-left: 4px solid #dc2626;
      padding: 15px;
      margin: 20px 0;
    }

    .footer {
      text-align: center;
      color: #666;
      font-size: 12px;
      margin-top: 30px;
      padding-top: 20px;
      border-top: 1px solid #ddd;
    }

    .link-box {
      background-color: #f3f4f6;
      padding: 15px;
      border-radius: 4px;
      word-break: break-all;
      margin: 15px 0;
      font-size: 14px;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="header">
      <h1>🔐 Reset Password Admin</h1>
      <p style="color: #666;">SIMPHONY - Sistem Informasi PMI</p>
    </div>

    <div class="content">
      <p>Halo,</p>

      <p>Kami menerima permintaan untuk mereset password akun admin Anda. Klik tombol di bawah ini untuk melanjutkan
        proses reset password:</p>

      <div style="text-align: center;">
        <a href="{{ url('/admin/reset-password/' . $token . '?email=' . urlencode($email)) }}"
           class="button">
          Reset Password Sekarang
        </a>
      </div>

      <div class="info-box">
        <strong>⚠️ Penting:</strong>
        <ul style="margin: 10px 0; padding-left: 20px;">
          <li>Link ini hanya berlaku selama <strong>1 jam</strong></li>
          <li>Jika Anda tidak meminta reset password, abaikan email ini</li>
          <li>Jangan bagikan link ini kepada siapapun</li>
        </ul>
      </div>

      <p style="margin-top: 20px; font-size: 14px; color: #666;">
        <strong>Jika tombol tidak berfungsi</strong>, salin dan tempel URL berikut ke browser Anda:
      </p>
      <div class="link-box">
        {{ url('/admin/reset-password/' . $token . '?email=' . urlencode($email)) }}
      </div>

      <p style="margin-top: 25px;">Terima kasih,<br><strong>Tim SIMPHONY</strong></p>
    </div>

    <div class="footer">
      <p>Email otomatis dari sistem SIMPHONY - PMI</p>
      <p>Jika ada pertanyaan, silakan hubungi administrator sistem</p>
      <p style="margin-top: 10px; color: #999;">© {{ date('Y') }} SIMPHONY. All rights reserved.</p>
    </div>
  </div>
</body>

</html>
