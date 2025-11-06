<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PemesananDarah;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get pending verifications count & latest orders
     * Endpoint untuk polling notifikasi
     */
    public function getPendingNotifications()
    {
        // Count pending verifications
        $pendingCount = PemesananDarah::where('status', 'pending')->count();

        // Get latest 5 pending orders (untuk dropdown notification)
        $latestOrders = PemesananDarah::where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get(['id', 'nama_pasien', 'rs_pemesan', 'gol_darah', 'rhesus', 'produk', 'jumlah_kantong', 'created_at'])
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'nama_pasien' => $order->nama_pasien,
                    'rs_pemesan' => $order->rs_pemesan,
                    'gol_darah' => $order->gol_darah . $order->rhesus,
                    'produk' => $order->produk,
                    'jumlah_kantong' => $order->jumlah_kantong,
                    'is_urgent' => $order->jumlah_kantong >= 10,
                    'created_at' => $order->created_at->diffForHumans(),
                    'created_at_full' => $order->created_at->format('d M Y H:i'),
                ];
            });

        return response()->json([
            'count' => $pendingCount,
            'latest_orders' => $latestOrders,
            'has_urgent' => $latestOrders->where('is_urgent', true)->isNotEmpty(),
        ]);
    }
}
