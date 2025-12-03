<?php

namespace App\Helpers;

class StokHelper
{
    public static function badgeStatus($total)
    {
        if ($total >= 50) {
            return ['Aman', 'bg-emerald-100 text-emerald-700'];
        }
        if ($total >= 10) {
            return ['Perhatian', 'bg-amber-100 text-amber-700'];
        }
        if ($total >= 1) {
            return ['Kritis', 'bg-rose-100 text-rose-700'];
        }
        return ['Habis', 'bg-slate-100 text-slate-700'];
    }

    public static function isKritis($jumlah)
    {
        return $jumlah < 10 && $jumlah > 0;
    }

    public static function statusLevel($jumlah)
    {
        if ($jumlah >= 50) return 'aman';
        if ($jumlah >= 10) return 'perhatian';
        if ($jumlah >= 1) return 'kritis';
        return 'habis';
    }
}
