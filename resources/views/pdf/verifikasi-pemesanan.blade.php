@php
  $statusConfig = [
      'approved' => ['label' => 'DISETUJUI', 'color' => '#059669', 'bg' => '#d1fae5'],
      'rejected' => ['label' => 'DITOLAK', 'color' => '#dc2626', 'bg' => '#fee2e2'],
      'pending' => ['label' => 'MENUNGGU VERIFIKASI', 'color' => '#d97706', 'bg' => '#fef3c7'],
  ];
  $statusKey = strtolower((string) ($status ?? 'pending'));
  $config = $statusConfig[$statusKey] ?? ['label' => strtoupper($statusKey), 'color' => '#6b7280', 'bg' => '#f3f4f6'];

  $rhesus = (string) data_get($pemesanan, 'rhesus', '');
  $tanggal = \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY');
  $idShort = sprintf('%03d', (int) ($pemesanan->id ?? 0));
  $produk = data_get($pemesanan, 'produk', '-') ?: '-';
  $gol = data_get($pemesanan, 'gol_darah', '-') ?: '-';
  $jumlah = data_get($pemesanan, 'jumlah_kantong', '-') ?: '-';
  $rsPemesan = data_get($pemesanan, 'rs_pemesan', '-') ?: '-';
  $nama = data_get($pemesanan, 'nama_pasien', '-') ?: '-';
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <style>
    @page {
      margin: 28px;
    }

    body {
      font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
      color: #0f172a;
    }

    .header {
      border-bottom: 3px solid #dc2626;
      padding-bottom: 8px;
      margin-bottom: 14px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .header img {
      width: 80px;
      height: 80px;
    }

    .badge {
      border: 2px solid {{ $config['color'] }};
      background: {{ $config['bg'] }};
      padding: 10px 24px;
      display: inline-block;
      border-radius: 4px;
      font-weight: 700;
      color: {{ $config['color'] }};
      letter-spacing: .6px;
    }

    table.detail {
      width: 100%;
      border-collapse: collapse;
      border: 1px solid #d1d5db;
      margin-top: 12px;
    }

    table.detail th,
    table.detail td {
      border: 1px solid #d1d5db;
      padding: 8px 10px;
      font-size: 12px;
    }

    table.detail thead th {
      background: #f9fafb;
      text-align: left;
    }

    .note {
      font-size: 12px;
      line-height: 1.7;
      text-align: justify;
    }
  </style>
</head>

<body>

  <div class="header">
    <img src="{{ public_path('images/Logo Nav New.png') }}"
         alt="SIMPHONY">
    <div>
      <div style="font-weight:700;color:#dc2626;">SIMPHONY</div>
      <div style="font-weight:700;">SISTEM INFORMASI PEMESANAN DAN INVENTORI</div>
      <div style="font-size:11px;color:#6b7280;">
        Jl. Sam Ratulangi No.105, Penengahan, Kec. Tj. Karang Bar., Kota Bandar Lampung, Lampung 35118 ·
        Telp: 0721 703020 · Email: info@simphony.id
      </div>
    </div>
  </div>

  <table width="100%">
    <tr>
      <td style="font-size:12px;">
        <strong>Nomor</strong><br>{{ $idShort }}/SIMPHONY/{{ date('Y') }}
      </td>
      <td style="font-size:12px;text-align:right;">
        Bandar Lampung, {{ $tanggal }}
      </td>
    </tr>
  </table>

  <p style="font-size:12px;margin:8px 0 0 0;"><strong>Perihal</strong> : Pemberitahuan Status Pemesanan Darah</p>

  <p style="font-size:12px;margin:12px 0 0 0;">Kepada
    Yth.<br><strong>{{ $nama }}</strong><br>{{ $rsPemesan }}</p>

  <p class="note"
     style="margin-top:12px;">Dengan hormat,</p>
  <p class="note">Melalui surat ini, kami sampaikan pemberitahuan terkait status permohonan pemesanan darah yang telah
    Bapak/Ibu ajukan dengan rincian sebagai berikut:</p>

  <div style="margin:6px 0 12px 0;">
    <span class="badge">STATUS: {{ $config['label'] }}</span>
  </div>

  <table class="detail">
    <thead>
      <tr>
        <th colspan="2">RINCIAN PEMESANAN</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="width:40%;">Nomor Pemesanan</td>
        <td><strong>#{{ $pemesanan->id ?? 'N/A' }}</strong></td>
      </tr>
      <tr>
        <td>Produk Darah</td>
        <td>{{ $produk }}</td>
      </tr>
      <tr>
        <td>Golongan Darah</td>
        <td><strong>{{ $gol }}{{ $rhesus ? ' ' . $rhesus : '' }}</strong></td>
      </tr>
      <tr>
        <td>Jumlah Kantong</td>
        <td>{{ $jumlah }} Kantong</td>
      </tr>
      <tr>
        <td>Rumah Sakit Pemesan</td>
        <td>{{ $rsPemesan }}</td>
      </tr>
    </tbody>
  </table>

  @if ($statusKey === 'approved')
    <p class="note"
       style="margin-top:12px;">
      Berdasarkan hasil verifikasi dan pengecekan ketersediaan stok, permohonan pemesanan darah Bapak/Ibu telah
      <strong>DISETUJUI</strong>.
      Dimohon untuk melakukan <strong>pengambilan darah paling lambat 2 × 24 jam</strong> sejak surat ini diterbitkan.
      Apabila terdapat kendala, silakan menghubungi petugas kami.
    </p>
  @elseif ($statusKey === 'rejected')
    <p class="note"
       style="margin-top:12px;">
      Berdasarkan verifikasi, permohonan pemesanan darah Bapak/Ibu <strong>BELUM DAPAT DIPENUHI</strong> saat ini karena
      keterbatasan stok.
      Silakan hubungi petugas untuk informasi ketersediaan stok atau prosedur pengajuan ulang.
    </p>
  @else
    <p class="note"
       style="margin-top:12px;">
      Permohonan Bapak/Ibu saat ini <strong>MENUNGGU VERIFIKASI</strong>. Kami akan menghubungi kembali setelah proses
      selesai.
    </p>
  @endif

</body>

</html>
