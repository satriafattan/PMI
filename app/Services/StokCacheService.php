<?php

namespace App\Services;

use App\Models\StokDarah;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StokCacheService
{
    /**
     * Cache duration dalam detik (5 menit)
     */
    const CACHE_TTL = 300;

    /**
     * Dapatkan ringkasan stok per golongan darah (dengan cache)
     */
    public static function getStokPerGolongan(): object
    {
        return Cache::remember('stok_per_golongan', self::CACHE_TTL, function () {
            return StokDarah::select(
                DB::raw('SUM(CASE WHEN gol_darah = "A" THEN jumlah ELSE 0 END) as A'),
                DB::raw('SUM(CASE WHEN gol_darah = "B" THEN jumlah ELSE 0 END) as B'),
                DB::raw('SUM(CASE WHEN gol_darah = "AB" THEN jumlah ELSE 0 END) as AB'),
                DB::raw('SUM(CASE WHEN gol_darah = "O" THEN jumlah ELSE 0 END) as O')
            )
                ->whereDate('tgl_kadaluarsa', '>=', now()->toDateString())
                ->first();
        });
    }

    /**
     * Dapatkan ringkasan stok per produk (dengan cache)
     */
    public static function getStokPerProduk(): array
    {
        return Cache::remember('stok_per_produk', self::CACHE_TTL, function () {
            return StokDarah::select('produk', DB::raw('SUM(jumlah) as total'))
                ->whereDate('tgl_kadaluarsa', '>=', now()->toDateString())
                ->groupBy('produk')
                ->pluck('total', 'produk')
                ->toArray();
        });
    }

    /**
     * Dapatkan jumlah stok kritis (dengan cache)
     */
    public static function getStokKritis(): int
    {
        return Cache::remember('stok_kritis_count', self::CACHE_TTL, function () {
            return StokDarah::where('jumlah', '<', 20)
                ->whereDate('tgl_kadaluarsa', '>=', now()->toDateString())
                ->count();
        });
    }

    /**
     * Dapatkan total stok (dengan cache)
     */
    public static function getTotalStok(): int
    {
        return Cache::remember('total_stok', self::CACHE_TTL, function () {
            return StokDarah::whereDate('tgl_kadaluarsa', '>=', now()->toDateString())
                ->sum('jumlah');
        });
    }

    /**
     * Hapus semua cache stok (panggil setelah update/insert/delete stok)
     */
    public static function clearCache(): void
    {
        Cache::forget('stok_per_golongan');
        Cache::forget('stok_per_produk');
        Cache::forget('stok_kritis_count');
        Cache::forget('total_stok');
    }

    /**
     * Cek stok tersedia untuk produk & golongan tertentu
     */
    public static function getStokTersedia(string $produk, string $golDarah, ?string $rhesus = null): int
    {
        $cacheKey = "stok_{$produk}_{$golDarah}_" . ($rhesus ?? 'any');

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($produk, $golDarah, $rhesus) {
            $query = StokDarah::where('produk', $produk)
                ->where('gol_darah', $golDarah)
                ->whereDate('tgl_kadaluarsa', '>=', now()->toDateString());

            if ($rhesus) {
                $query->where('rhesus', $rhesus);
            }

            return (int) $query->sum('jumlah');
        });
    }
}
