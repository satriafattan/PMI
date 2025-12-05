# Table Sorting Feature - Dokumentasi Implementasi

## 📋 Overview

Fitur sorting tabel yang simpel dan reusable untuk semua tabel di admin panel.

## 🎯 Fitur

-   ✅ Klik header untuk sort ascending/descending
-   ✅ Visual indicator (icon panah merah untuk kolom aktif)
-   ✅ Keyboard accessible (Enter/Space untuk sort)
-   ✅ Hover effect untuk UX yang lebih baik
-   ✅ Auto-detect tipe data (angka vs string)
-   ✅ Simpel dan mudah digunakan

## 📝 Cara Implementasi di Tabel Baru

### 1. Update Header Tabel

Ganti `<th>` biasa dengan komponen `<x-sortable-th>`:

**Sebelum:**

```blade
<thead class="bg-neutral-50 text-neutral-600">
  <tr class="text-left">
    <th class="px-4 py-3 font-medium">Nama Pasien</th>
    <th class="px-4 py-3 font-medium">Rumah Sakit</th>
    <th class="px-4 py-3 font-medium">Aksi</th>
  </tr>
</thead>
```

**Sesudah:**

```blade
<thead class="bg-neutral-50 text-neutral-600">
  <tr class="text-left">
    <x-sortable-th column="nama_pasien" label="Nama Pasien" />
    <x-sortable-th column="rs_pemesan" label="Rumah Sakit" />
    <th class="px-4 py-3 font-medium">Aksi</th> <!-- Kolom tanpa sorting -->
  </tr>
</thead>
```

### 2. Tambahkan Data Attributes di Body Tabel

Tambahkan `data-{column}` attribute di setiap `<td>`:

**Untuk server-side rendering (Blade):**

```blade
<tr>
  <td class="px-4 py-3" data-nama_pasien="{{ $item->nama_pasien }}">
    {{ $item->nama_pasien }}
  </td>
  <td class="px-4 py-3" data-rs_pemesan="{{ $item->rs_pemesan }}">
    {{ $item->rs_pemesan }}
  </td>
</tr>
```

**Untuk client-side rendering (JavaScript):**

```javascript
function renderTable(data) {
    const tbody = document.getElementById("tableBody");
    tbody.innerHTML = data
        .map(
            (item) => `
    <tr>
      <td class="px-4 py-3" data-nama_pasien="${item.nama}">
        ${item.nama}
      </td>
      <td class="px-4 py-3" data-rs_pemesan="${item.rs}">
        ${item.rs}
      </td>
    </tr>
  `
        )
        .join("");
}
```

### 3. Inisialisasi TableSorter

Tambahkan di script JavaScript (biasanya dalam `DOMContentLoaded`):

```javascript
document.addEventListener("DOMContentLoaded", () => {
    // ... kode lainnya ...

    // Initialize Table Sorter
    if (typeof TableSorter !== "undefined") {
        new TableSorter("#namaTableId");
    }
});
```

**Dengan options (opsional):**

```javascript
new TableSorter("#namaTableId", {
    defaultSort: "nama_pasien", // Kolom default untuk sort
    defaultDirection: "asc", // Arah default: 'asc' atau 'desc'
    onSort: (column, direction) => {
        // Callback setelah sort
        console.log(`Sorted by ${column} ${direction}`);
    },
});
```

### 4. Tambahkan ID ke Table

Pastikan table memiliki ID:

```blade
<table id="namaTableId" class="min-w-full text-sm">
  <!-- ... -->
</table>
```

## 🎨 Contoh Lengkap

**File: resources/views/admin/example/index.blade.php**

```blade
<div class="overflow-hidden rounded-2xl border border-neutral-200 bg-white">
  <div class="overflow-x-auto">
    <table id="exampleTable" class="min-w-full text-sm">
      <thead class="bg-neutral-50 text-neutral-600">
        <tr class="text-left">
          <x-sortable-th column="nama" label="Nama" />
          <x-sortable-th column="email" label="Email" />
          <x-sortable-th column="created_at" label="Tanggal Dibuat" />
          <th class="px-4 py-3 font-medium">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($items as $item)
          <tr>
            <td class="px-4 py-3" data-nama="{{ $item->nama }}">{{ $item->nama }}</td>
            <td class="px-4 py-3" data-email="{{ $item->email }}">{{ $item->email }}</td>
            <td class="px-4 py-3" data-created_at="{{ $item->created_at }}">
              {{ $item->created_at->format('d-m-Y') }}
            </td>
            <td class="px-4 py-3">
              <button>Detail</button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    new TableSorter('#exampleTable', {
      defaultSort: 'nama',
      defaultDirection: 'asc'
    });
  });
</script>
@endpush
```

## 📌 Tips

1. **Kolom tanpa sorting:** Gunakan `<th>` biasa tanpa komponen `<x-sortable-th>`
2. **Sorting angka:** Data attributes akan otomatis di-parse sebagai angka jika valid
3. **Sorting tanggal:** Gunakan format ISO (YYYY-MM-DD) di data attribute untuk hasil yang akurat
4. **Custom sorting:** Bisa override dengan menambahkan logic di callback `onSort`

## ✅ Tabel yang Sudah Diimplementasi

-   ✅ Riwayat Pemesanan (`admin/riwayat`)
-   ✅ Verifikasi Pemesanan (`admin/verifikasi`)

## 🔧 Troubleshooting

**Sorting tidak bekerja?**

-   Pastikan assets sudah di-build: `npm run build`
-   Clear cache view: `php artisan view:clear`
-   Periksa console browser untuk error JavaScript
-   Pastikan data attributes ada di semua `<td>`

**Icon tidak muncul?**

-   Pastikan Tailwind classes ter-compile dengan benar
-   Refresh browser dengan hard reload (Ctrl + Shift + R)

## 🎯 Future Enhancements

-   [ ] Sort multi-kolom (shift + click)
-   [ ] Persist sort state di localStorage
-   [ ] Custom comparator per kolom
-   [ ] Server-side sorting untuk dataset besar
