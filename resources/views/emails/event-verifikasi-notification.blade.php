@php
  // ====== Konfigurasi status ======
  $statusConfig = [
      'approved' => ['label' => 'DISETUJUI', 'color' => '#059669', 'bg' => '#d1fae5'],
      'rejected' => ['label' => 'DITOLAK', 'color' => '#dc2626', 'bg' => '#fee2e2'],
  ];
  $statusKey = strtolower((string) ($status ?? 'pending'));
  $config = $statusConfig[$statusKey] ?? ['label' => strtoupper($statusKey), 'color' => '#6b7280', 'bg' => '#f3f4f6'];

  // ====== Data aman ======
  $tanggal = \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY');
  $idShort = sprintf('%03d', (int) ($event->id ?? 0));
  $nama = data_get($event, 'nama', '-') ?: '-';
  $institusi = data_get($event, 'institusi_pemohon', '-') ?: '-';
  $jenisEvent = data_get($event, 'jenis_event', '-') ?: '-';
  $tanggalEvent = data_get($event, 'tanggal_event')
      ? \Carbon\Carbon::parse($event->tanggal_event)->locale('id')->isoFormat('D MMMM YYYY')
      : '-';
  $jamMulai = data_get($event, 'jam_mulai') ? \Illuminate\Support\Str::of($event->jam_mulai)->substr(0, 5) : '-';
  $jamSelesai = data_get($event, 'jam_selesai') ? \Illuminate\Support\Str::of($event->jam_selesai)->substr(0, 5) : '-';
  $lokasi = data_get($event, 'lokasi_lengkap', '-') ?: '-';
  $nomorWA = '0721-703020'; // Nomor WhatsApp PMI - sesuaikan dengan nomor yang benar
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width,initial-scale=1.0">
  <title>Notifikasi Verifikasi Event</title>
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
                    <img src="{{ asset('images/Logo Nav New.png') }}"
                         alt="SIMPHONY"
                         width="56"
                         height="56"
                         style="display:block;border:0;">
                  </td>
                  <td style="text-align:left;">
                    <h2 style="color:#dc2626;margin:0 0 4px 0;font-size:20px;font-weight:bold;letter-spacing:.5px;">
                      SIMPHONY</h2>
                    <h3 style="color:#1f2937;margin:0 0 6px 0;font-size:18px;font-weight:bold;">SISTEM INFORMASI
                      PEMESANAN DAN INVENTORI</h3>
                    <p style="margin:0;font-size:12px;color:#4b5563;line-height:1.6;">
                      Jl. Sam Ratulangi No.105, Penengahan, Kec. Tj. Karang Bar., Kota Bandar Lampung, Lampung 35118<br>
                      Telp: 0721 703020 | Email: info@simphony.id
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
                      {{ $idShort }}/EVENT-SIMPHONY/{{ date('Y') }}
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
                <strong>Perihal</strong> : Pemberitahuan Status Pengajuan Event
              </p>
            </td>
          </tr>

          <!-- Kepada -->
          <tr>
            <td style="padding:20px 40px 10px 40px;">
              <p style="margin:0 0 5px 0;font-size:13px;color:#374151;">Kepada Yth.</p>
              <p style="margin:0;font-size:13px;color:#374151;">
                <strong>{{ $nama }}</strong><br>
                {{ $institusi }}
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
                Melalui surat elektronik ini, kami sampaikan pemberitahuan terkait status pengajuan kegiatan event donor
                darah
                yang telah Bapak/Ibu ajukan dengan rincian sebagai berikut:
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

          <!-- Tabel Rincian Event -->
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
                    <strong style="color:#1f2937;font-size:14px;">RINCIAN EVENT</strong>
                  </td>
                </tr>
                <tr>
                  <td style="border:1px solid #d1d5db;padding:10px;width:40%;color:#374151;font-size:13px;">Nomor
                    Pengajuan</td>
                  <td style="border:1px solid #d1d5db;padding:10px;color:#1f2937;font-size:13px;">
                    <strong>#{{ $event->id ?? 'N/A' }}</strong>
                  </td>
                </tr>
                <tr style="background:#f9fafb;">
                  <td style="border:1px solid #d1d5db;padding:10px;color:#374151;font-size:13px;">Jenis Event</td>
                  <td style="border:1px solid #d1d5db;padding:10px;color:#1f2937;font-size:13px;">{{ $jenisEvent }}
                  </td>
                </tr>
                <tr>
                  <td style="border:1px solid #d1d5db;padding:10px;color:#374151;font-size:13px;">Tanggal Event</td>
                  <td style="border:1px solid #d1d5db;padding:10px;color:#1f2937;font-size:13px;">
                    <strong>{{ $tanggalEvent }}</strong>
                  </td>
                </tr>
                <tr style="background:#f9fafb;">
                  <td style="border:1px solid #d1d5db;padding:10px;color:#374151;font-size:13px;">Waktu Pelaksanaan</td>
                  <td style="border:1px solid #d1d5db;padding:10px;color:#1f2937;font-size:13px;">{{ $jamMulai }} -
                    {{ $jamSelesai }} WIB</td>
                </tr>
                <tr>
                  <td style="border:1px solid #d1d5db;padding:10px;color:#374151;font-size:13px;">Lokasi</td>
                  <td style="border:1px solid #d1d5db;padding:10px;color:#1f2937;font-size:13px;">{{ $lokasi }}
                  </td>
                </tr>
                <tr style="background:#f9fafb;">
                  <td style="border:1px solid #d1d5db;padding:10px;color:#374151;font-size:13px;">Pengaju</td>
                  <td style="border:1px solid #d1d5db;padding:10px;color:#1f2937;font-size:13px;">{{ $nama }}
                    ({{ $institusi }})</td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Keterangan Status -->
          <tr>
            <td style="padding:0 40px 20px 40px;">
              @if ($statusKey === 'approved')
                <table width="100%"
                       cellpadding="0"
                       cellspacing="0"
                       border="0"
                       style="background:#ecfdf5;border-left:4px solid #059669;">
                  <tr>
                    <td style="padding:15px;">
                      <p style="margin:0 0 8px 0;font-size:13px;color:#374151;line-height:1.8;text-align:justify;">
                        Berdasarkan hasil evaluasi dan verifikasi, dengan ini kami sampaikan bahwa pengajuan event donor
                        darah
                        Bapak/Ibu telah <strong>DISETUJUI</strong>.
                      </p>
                      @if ($catatan)
                        <p style="margin:8px 0 0 0;font-size:13px;color:#374151;line-height:1.8;text-align:justify;">
                          <strong>Catatan:</strong> {{ $catatan }}
                        </p>
                      @endif
                    </td>
                  </tr>
                </table>
              @elseif ($statusKey === 'rejected')
                <table width="100%"
                       cellpadding="0"
                       cellspacing="0"
                       border="0"
                       style="background:#fef2f2;border-left:4px solid #dc2626;">
                  <tr>
                    <td style="padding:15px;">
                      <p style="margin:0 0 8px 0;font-size:13px;color:#374151;line-height:1.8;text-align:justify;">
                        Berdasarkan hasil evaluasi, pengajuan event donor darah Bapak/Ibu <strong>BELUM DAPAT
                          DISETUJUI</strong> saat ini.
                      </p>
                      @if ($catatan)
                        <p style="margin:8px 0 0 0;font-size:13px;color:#374151;line-height:1.8;text-align:justify;">
                          <strong>Alasan:</strong> {{ $catatan }}
                        </p>
                      @endif
                    </td>
                  </tr>
                </table>
              @endif
            </td>
          </tr>

          <!-- Info Kontak WhatsApp -->
          <tr>
            <td style="padding:0 40px 20px 40px;">
              <table width="100%"
                     cellpadding="0"
                     cellspacing="0"
                     border="0"
                     style="background:#eff6ff;border:2px solid #3b82f6;border-radius:8px;">
                <tr>
                  <td style="padding:18px;">
                    <table width="100%"
                           cellpadding="0"
                           cellspacing="0"
                           border="0">
                      <tr>
                        <td style="width:48px;vertical-align:top;">
                          <svg width="36"
                               height="36"
                               viewBox="0 0 24 24"
                               fill="none"
                               xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"
                                  fill="#25D366" />
                          </svg>
                        </td>
                        <td style="padding-left:12px;">
                          <p style="margin:0 0 6px 0;font-size:14px;color:#1e40af;font-weight:bold;">
                            Butuh Informasi Lebih Lanjut?
                          </p>
                          <p style="margin:0 0 8px 0;font-size:13px;color:#374151;line-height:1.6;">
                            Untuk informasi lebih detail atau koordinasi terkait event donor darah,
                            silakan hubungi tim kami melalui WhatsApp:
                          </p>
                          <p style="margin:0;font-size:14px;">
                            <a href="https://wa.me/{{ str_replace(['-', ' '], '', $nomorWA) }}"
                               style="color:#25D366;text-decoration:none;font-weight:bold;">
                              📱 {{ $nomorWA }} (WhatsApp)
                            </a>
                          </p>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Penutup -->
          <tr>
            <td style="padding:0 40px 20px 40px;">
              <p style="margin:0 0 10px 0;font-size:13px;color:#374151;line-height:1.8;text-align:justify;">
                Demikian pemberitahuan ini kami sampaikan. Atas perhatian dan kerjasamanya dalam mendukung kegiatan
                donor darah,
                kami ucapkan terima kasih.
              </p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>

</html>
