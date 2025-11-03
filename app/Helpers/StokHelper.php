<?php

namespace App\Helpers;

class StokHelper
{
    public static function badgeStatus($total)
    {
        if ($total >= 80) {
            return ['Aman', 'bg-emerald-100 text-emerald-700'];
        }
        if ($total >= 40) {
            return ['Perhatian', 'bg-amber-100 text-amber-700'];
        }
        return ['Kritis', 'bg-rose-100 text-rose-700'];
    }

    public static function isKritis($jumlah)
    {
        return $jumlah < 20;
    }

    public static function statusLevel($jumlah)
    {
        if ($jumlah >= 80) return 'aman';
        if ($jumlah >= 40) return 'menipis';
        return 'kritis';
    }
}
