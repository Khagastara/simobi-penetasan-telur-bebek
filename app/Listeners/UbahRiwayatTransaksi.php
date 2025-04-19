<?php

namespace App\Listeners;

use App\Events\UbahStatusTransaksi;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UbahRiwayatTransaksi
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UbahStatusTransaksi $event): void
    {
        //
    }
}
