<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DarahKeluarExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithProperties,
    WithStyles,
    WithColumnWidths
{
    public function __construct(
        private Collection $rows
    ) {}

    public function collection()
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            'ID Darah',
            'Golongan Darah',
            'Rhesus',
            'Produk',
            'Rumah Sakit Penerima',
            'Tanggal Keluar',
            'Status',
        ];
    }

    public function map($row): array
    {
        return [
            $row['id'] ?? '-',
            $row['gol'] ?? '-',
            $row['rh'] ?? '-',
            $row['produk'] ?? '-',
            $row['penerima'] ?? '-',
            $row['keluar'] ?? '-',
            $row['status'] ?? '-',
        ];
    }

    public function properties(): array
    {
        return [
            'title'       => 'Data Darah Keluar',
            'description' => 'Daftar unit darah yang sudah keluar',
            'subject'     => 'Stok Darah',
            'keywords'    => 'darah, keluar, riwayat, simphony',
            'category'    => 'Laporan',
            'manager'     => 'SIMPHONY',
            'company'     => 'SIMPHONY - Sistem Informasi Pemesanan dan Inventori',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFF3E0']
                ]
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,  // ID Darah
            'B' => 18,  // Golongan Darah
            'C' => 12,  // Rhesus
            'D' => 20,  // Produk
            'E' => 30,  // Rumah Sakit Penerima
            'F' => 18,  // Tanggal Keluar
            'G' => 15,  // Status
        ];
    }
}
