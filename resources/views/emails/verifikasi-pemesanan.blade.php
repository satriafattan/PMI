@php
  // helper kecil untuk label status
  $statusLabel = [
    'approved' => 'Disetujui',
    'rejected' => 'Ditolak',
    'pending'  => 'Menunggu',
  ][$status] ?? ucfirst($status);

  $rhesus = $pemesanan->rhesus ? $pemesanan->rhesus : '';
@endphp

@component('mail::message')
# Pemberitahuan Status Pemesanan Darah

Yth. **Bapak/Ibu {{ $pemesanan->nama_pasien }}**,

Dengan hormat, melalui email ini kami menyampaikan status terbaru atas permohonan pemesanan darah Anda.

@component('mail::panel')
**Status:** {{ $statusLabel }}  
**Produk:** {{ $pemesanan->produk ?? '-' }}  
**Golongan:** {{ $pemesanan->gol_darah ?? '-' }}{{ $rhesus ? ' ' . $rhesus : '' }}  
**Jumlah Kantong:** {{ $pemesanan->jumlah_kantong ?? '-' }}  
**Rumah Sakit Pemesan:** {{ $pemesanan->rs_pemesan ?? '-' }}
@endcomponent

@if($status === 'approved')
Permohonan Anda telah **disetujui**.  
Mohon melakukan **pengambilan darah selambat-lambatnya dalam 2×24 jam** sejak pemberitahuan ini, atau menghubungi petugas UDD untuk penjadwalan.

@component('mail::button', ['url' => url('/')])
Hubungi UDD PMI Provinsi Lampung
@endcomponent

@elseif($status === 'rejected')
Mohon maaf, permohonan Anda **belum dapat kami penuhi** saat ini.  
Untuk informasi lebih lanjut terkait ketersediaan stok atau prosedur pengajuan ulang, silakan menghubungi petugas UDD.

@component('mail::button', ['url' => url('/')])
Informasi Kontak UDD
@endcomponent

@else
Status permohonan Anda saat ini **{{ strtolower($statusLabel) }}**. Kami akan menghubungi Anda setelah proses verifikasi selesai.
@endif

Salam hormat,  
**Unit Donor Darah PMI Provinsi Lampung**  
Jl. Sam Ratulangi No.105, Penengahan, Kec. Tj. Karang Bar., Kota Bandar Lampung, Lampung 35118  
Telp: 0721 703020 · Email: lampung@pmi.ac.id
@endcomponent
