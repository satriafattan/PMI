<?php

namespace App\Events;

use App\Models\PemesananDarah;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PemesananBaruEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pemesanan;

    /**
     * Create a new event instance.
     */
    public function __construct(PemesananDarah $pemesanan)
    {
        $this->pemesanan = $pemesanan;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('admin-notifications'),
        ];
    }

    /**
     * Data yang di-broadcast
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->pemesanan->id,
            'nama_pasien' => $this->pemesanan->nama_pasien,
            'rs_pemesan' => $this->pemesanan->rs_pemesan,
            'gol_darah' => $this->pemesanan->gol_darah,
            'rhesus' => $this->pemesanan->rhesus,
            'produk' => $this->pemesanan->produk,
            'jumlah_kantong' => $this->pemesanan->jumlah_kantong,
            'is_urgent' => $this->pemesanan->jumlah_kantong >= 10, // Urgent jika >= 10 kantong
            'created_at' => $this->pemesanan->created_at->format('H:i'),
        ];
    }

    /**
     * Nama event yang akan di-broadcast
     */
    public function broadcastAs(): string
    {
        return 'pemesanan.baru';
    }
}
