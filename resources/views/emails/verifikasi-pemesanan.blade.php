@php
  // ====== Konfigurasi status ======
  $statusConfig = [
      'approved' => ['label' => 'DISETUJUI', 'color' => '#059669', 'bg' => '#d1fae5'],
      'rejected' => ['label' => 'DITOLAK', 'color' => '#dc2626', 'bg' => '#fee2e2'],
      'pending' => ['label' => 'MENUNGGU VERIFIKASI', 'color' => '#d97706', 'bg' => '#fef3c7'],
  ];
  $statusKey = strtolower((string) ($status ?? 'pending'));
  $config = $statusConfig[$statusKey] ?? ['label' => strtoupper($statusKey), 'color' => '#6b7280', 'bg' => '#f3f4f6'];

  // ====== Data aman ======
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
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width,initial-scale=1.0">
  <title>Verifikasi Pemesanan Darah</title>
</head>

<body style="margin:0;padding:0;font-family:'Times New Roman',Times,serif;background-color:#f5f5f5;">
  <table width="100%"
         cellpadding="0"
         cellspacing="0"
         border="0"
         style="background-color:#f5f5f5;padding:30px 0;">
    <tr>
      <td align="center">
        <table width="650"
               cellpadding="0"
               cellspacing="0"
               border="0"
               style="background:#ffffff;box-shadow:0 2px 8px rgba(0,0,0,.1);">
          <!-- Kop Surat -->
          <tr>
            <td style="border-bottom:3px solid #dc2626;padding:20px 40px;">
              <table width="100%"
                     cellpadding="0"
                     cellspacing="0"
                     border="0">
                <tr>
                  <td style="width:72px;vertical-align:top;">
                    <img src="{{ $message->embed(public_path('images/LOGO NAV.png')) }}"
                         alt="PMI"
                         width="66"
                         height="66"
                         style="display:block;border:0;">
                  </td>
                  <td style="text-align:left;">
                    <h2 style="color:#dc2626;margin:0 0 4px 0;font-size:20px;font-weight:bold;letter-spacing:.5px;">
                      PALANG MERAH INDONESIA</h2>
                    <h3 style="color:#1f2937;margin:0 0 6px 0;font-size:18px;font-weight:bold;">UNIT DONOR DARAH
                      PROVINSI LAMPUNG</h3>
                    <p style="margin:0;font-size:12px;color:#4b5563;line-height:1.6;">
                      Jl. Sam Ratulangi No.105, Penengahan, Kec. Tj. Karang Bar., Kota Bandar Lampung, Lampung 35118<br>
                      Telp: 0721 703020 | Email: lampung@pmi.ac.id
                    </p>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Nomor & Tanggal -->
          <tr>
            <td style="padding:25px 40px 15px 40px;">
              <table width="100%"
                     cellpadding="0"
                     cellspacing="0"
                     border="0">
                <tr>
                  <td style="width:50%;">
                    <p style="margin:0;font-size:13px;color:#374151;">
                      <strong>Nomor</strong><br>
                      {{ $idShort }}/UDD-PMI-LAMP/{{ date('Y') }}
                    </p>
                  </td>
                  <td style="width:50%;text-align:right;">
                    <p style="margin:0;font-size:13px;color:#374151;">
                      Bandar Lampung, {{ $tanggal }}
                    </p>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Perihal -->
          <tr>
            <td style="padding:10px 40px;">
              <p style="margin:0;font-size:13px;color:#374151;line-height:1.8;">
                <strong>Perihal</strong> : Pemberitahuan Status Pemesanan Darah
              </p>
            </td>
          </tr>

          <!-- Kepada -->
          <tr>
            <td style="padding:20px 40px 10px 40px;">
              <p style="margin:0 0 5px 0;font-size:13px;color:#374151;">Kepada Yth.</p>
              <p style="margin:0;font-size:13px;color:#374151;">
                <strong>{{ $nama }}</strong><br>
                {{ $rsPemesan }}
              </p>
            </td>
          </tr>

          <!-- Salam -->
          <tr>
            <td style="padding:20px 40px 15px 40px;">
              <p style="margin:0;font-size:13px;color:#374151;line-height:1.8;text-align:justify;">Dengan hormat,</p>
            </td>
          </tr>

          <!-- Pengantar -->
          <tr>
            <td style="padding:0 40px 20px 40px;">
              <p style="margin:0 0 15px 0;font-size:13px;color:#374151;line-height:1.8;text-align:justify;">
                Melalui surat elektronik ini, kami sampaikan pemberitahuan terkait status permohonan pemesanan darah
                yang telah Bapak/Ibu ajukan kepada Unit Donor Darah PMI Provinsi Lampung dengan rincian sebagai berikut:
              </p>
            </td>
          </tr>

          <!-- Status Badge -->
          <tr>
            <td style="padding:0 40px 20px 40px;">
              <table width="100%"
                     cellpadding="0"
                     cellspacing="0"
                     border="0">
                <tr>
                  <td align="center">
                    <table cellpadding="0"
                           cellspacing="0"
                           border="0"
                           style="background:{{ $config['bg'] }};border:2px solid {{ $config['color'] }};">
                      <tr>
                        <td style="padding:12px 40px;text-align:center;">
                          <strong style="color:{{ $config['color'] }};font-size:15px;letter-spacing:1px;">STATUS:
                            {{ $config['label'] }}</strong>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Tabel Rincian -->
          <tr>
            <td style="padding:0 40px 20px 40px;">
              <table width="100%"
                     cellpadding="10"
                     cellspacing="0"
                     border="1"
                     style="border-collapse:collapse;border:1px solid #d1d5db;">
                <tr style="background:#f9fafb;">
                  <td colspan="2"
                      style="border:1px solid #d1d5db;padding:12px;">
                    <strong style="color:#1f2937;font-size:14px;">RINCIAN PEMESANAN</strong>
                  </td>
                </tr>
                <tr>
                  <td style="border:1px solid #d1d5db;padding:10px;width:40%;color:#374151;font-size:13px;">Nomor
                    Pemesanan</td>
                  <td style="border:1px solid #d1d5db;padding:10px;color:#1f2937;font-size:13px;">
                    <strong>#{{ $pemesanan->id ?? 'N/A' }}</strong></td>
                </tr>
                <tr style="background:#f9fafb;">
                  <td style="border:1px solid #d1d5db;padding:10px;color:#374151;font-size:13px;">Produk Darah</td>
                  <td style="border:1px solid #d1d5db;padding:10px;color:#1f2937;font-size:13px;">{{ $produk }}
                  </td>
                </tr>
                <tr>
                  <td style="border:1px solid #d1d5db;padding:10px;color:#374151;font-size:13px;">Golongan Darah</td>
                  <td style="border:1px solid #d1d5db;padding:10px;color:#1f2937;font-size:13px;">
                    <strong>{{ $gol }}{{ $rhesus ? ' ' . $rhesus : '' }}</strong></td>
                </tr>
                <tr style="background:#f9fafb;">
                  <td style="border:1px solid #d1d5db;padding:10px;color:#374151;font-size:13px;">Jumlah Kantong</td>
                  <td style="border:1px solid #d1d5db;padding:10px;color:#1f2937;font-size:13px;">{{ $jumlah }}
                    Kantong</td>
                </tr>
                <tr>
                  <td style="border:1px solid #d1d5db;padding:10px;color:#374151;font-size:13px;">Rumah Sakit Pemesan
                  </td>
                  <td style="border:1px solid #d1d5db;padding:10px;color:#1f2937;font-size:13px;">{{ $rsPemesan }}
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Keterangan Status -->
          <tr>
            <td style="padding:0 40px 20px 40px;">
              @if ($statusKey === 'approved')
                <p style="margin:0 0 10px 0;font-size:13px;color:#374151;line-height:1.8;text-align:justify;">
                  Berdasarkan hasil verifikasi dan pengecekan ketersediaan stok, dengan ini kami sampaikan bahwa
                  permohonan pemesanan darah Bapak/Ibu telah <strong>DISETUJUI</strong>.
                </p>
                <p style="margin:0;font-size:13px;color:#374151;line-height:1.8;text-align:justify;">
                  Dimohon untuk melakukan <strong>pengambilan darah paling lambat 2 × 24 jam</strong> sejak surat
                  elektronik ini diterbitkan.
                  Apabila terdapat kendala, silakan menghubungi petugas Unit Donor Darah.
                </p>
              @elseif ($statusKey === 'rejected')
                <table width="100%"
                       cellpadding="0"
                       cellspacing="0"
                       border="0"
                       style="background:#fef2f2;border-left:4px solid #dc2626;">
                  <tr>
                    <td style="padding:15px;">
                      <p style="margin:0 0 8px 0;font-size:13px;color:#374151;line-height:1.8;text-align:justify;">
                        Berdasarkan verifikasi, permohonan pemesanan darah Bapak/Ibu <strong>BELUM DAPAT
                          DIPENUHI</strong> saat ini,
                        dikarenakan stok darah yang diminta tidak tersedia.
                      </p>
                      <p style="margin:0;font-size:13px;color:#374151;line-height:1.8;text-align:justify;">
                        Silakan hubungi petugas untuk informasi ketersediaan stok atau prosedur pengajuan ulang.
                      </p>
                    </td>
                  </tr>
                </table>
              @else
                <table width="100%"
                       cellpadding="0"
                       cellspacing="0"
                       border="0"
                       style="background:#fffbeb;border-left:4px solid #d97706;">
                  <tr>
                    <td style="padding:15px;">
                      <p style="margin:0;font-size:13px;color:#374151;line-height:1.8;text-align:justify;">
                        Permohonan Bapak/Ibu saat ini <strong>MENUNGGU VERIFIKASI</strong>. Kami akan menghubungi
                        kembali setelah proses selesai.
                      </p>
                    </td>
                  </tr>
                </table>
              @endif
            </td>
          </tr>

          <!-- Penutup -->
          <tr>
            <td style="padding:0 40px 20px 40px;">
              <p style="margin:0 0 10px 0;font-size:13px;color:#374151;line-height:1.8;text-align:justify;">
                Demikian pemberitahuan ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.
              </p>
            </td>
          </tr>

          <!-- Tanda Tangan + Cap -->
          <tr>
            <td style="padding:0 40px 40px 40px;">
              <table width="100%"
                     cellpadding="0"
                     cellspacing="0"
                     border="0">
                <tr>
                  <td style="width:50%;"></td>
                  <td style="width:50%;text-align:center;">
                    <p style="margin:0 0 5px 0;font-size:13px;color:#374151;">Hormat kami,</p>
                    <p style="margin:0 0 14px 0;font-size:13px;color:#374151;font-weight:bold;">Unit Donor Darah PMI
                      Provinsi Lampung</p>

                    <!-- Cap PNG transparan (email aman) -->
                    <img src="{{ $message->embed(public_path('images/cap-pmi.png')) }}"
                         alt="Cap PMI"
                         width="120"
                         style="display:block;margin:0 auto 28px auto;border:0;">

                    <!-- Nama Jabatan -->
                    <p
                       style="margin:0;font-size:13px;color:#374151;border-bottom:1px solid #374151;display:inline-block;padding-bottom:2px;">
                      <strong>Kepala Unit Donor Darah</strong>
                    </p>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Footer minimal -->
          <tr>
            <td style="padding:18px 40px;border-top:1px solid #e5e7eb;">
              <table width="100%"
                     cellpadding="0"
                     cellspacing="0"
                     border="0">
                <tr>
                  <td style="font-size:12px;color:#6b7280;line-height:1.7;">
                    <strong style="color:#111827;">UDD PMI Provinsi Lampung</strong><br>
                    Jl. Sam Ratulangi No.105, Penengahan, Tj. Karang Bar., Kota Bandar Lampung 35118<br>
                    Telp: 0721 703020 · Email: lampung@pmi.ac.id
                    <div style="margin-top:6px;font-size:11px;color:#9ca3af;">
                      Email ini dikirim otomatis oleh sistem. Jika data tidak sesuai, mohon hubungi kontak di atas.
                    </div>
                  </td>
                  <td align="right"
                      style="font-size:11px;color:#9ca3af;white-space:nowrap;">
                    © {{ date('Y') }} PMI Lampung
                  </td>
                </tr>
              </table>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>

</html>
