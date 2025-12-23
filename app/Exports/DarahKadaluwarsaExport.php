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

class DarahKadaluwarsaExport implements
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
            'Tanggal Masuk',
            'Tanggal Kadaluwarsa',
        ];
    }

    public function map($row): array
    {
        return [
            $row['id'] ?? '-',
            $row['gol'] ?? '-',
            $row['rh'] ?? '-',
            $row['produk'] ?? '-',
            $row['masuk'] ?? '-',
            $row['exp'] ?? '-',
        ];
    }

    public function properties(): array
    {
        return [
            'title'       => 'Data Darah Kadaluwarsa',
            'description' => 'Daftar unit darah yang sudah kadaluwarsa',
            'subject'     => 'Stok Darah',
            'keywords'    => 'darah, kadaluwarsa, expired, simphony',
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
                    'startColor' => ['rgb' => 'FFEBEE']
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
            'E' => 18,  // Tanggal Masuk
            'F' => 20,  // Tanggal Kadaluwarsa
        ];
    }
}
