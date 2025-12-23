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

class PemesananExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithProperties,
    WithStyles,
    WithColumnWidths
{
    public function __construct(
        private Collection $rows,
        private string $periode
    ) {}

    public function collection()
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Dibuat Pada',
            'Tanggal Pemesanan',
            'RS Pemesan',
            'Nama Pasien',
            'Produk',
            'Gol',
            'Rhesus',
            'Jumlah Kantong',
            'Status',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            optional($row->created_at)->format('Y-m-d H:i'),
            optional($row->tanggal_pemesanan)->format('Y-m-d'),
            $row->rs_pemesan,
            $row->nama_pasien,
            $row->produk,
            $row->gol_darah,
            $row->rhesus,
            (int) $row->jumlah_kantong,
            $row->status,
        ];
    }

    public function properties(): array
    {
        return [
            'title'       => 'Laporan Pemesanan Darah',
            'description' => 'Periode: ' . $this->periode,
            'subject'     => 'Laporan',
            'keywords'    => 'pemesanan, darah, simphony, laporan',
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
                    'startColor' => ['rgb' => 'E3F2FD']
                ]
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ID
            'B' => 18,  // Dibuat Pada
            'C' => 18,  // Tanggal Pemesanan
            'D' => 30,  // RS Pemesan
            'E' => 25,  // Nama Pasien
            'F' => 15,  // Produk
            'G' => 8,   // Gol
            'H' => 10,  // Rhesus
            'I' => 15,  // Jumlah Kantong
            'J' => 15,  // Status
        ];
    }
}
