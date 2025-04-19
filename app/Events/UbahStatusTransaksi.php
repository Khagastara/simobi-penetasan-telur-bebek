<?php
namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaksi;

class UbahStatusTransaksi
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Transaksi $transaksi,
        public string $oldStatus,
        public string $newStatus
    ) {}
}
