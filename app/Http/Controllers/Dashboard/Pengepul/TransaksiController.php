<?php

namespace App\Http\Controllers\Dashboard\Pengepul;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pengepul\PengepulBuatTransaksiRequest;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function store(PengepulBuatTransaksiRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $transaksi = Transaksi::create([
                'id_pengepul' => $request->user()->id_pengepul,
                'id_stok' => $request->id_stok,
                'kuantitas' => $request->kuantitas,
                'total' => $request->total,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status' => $request->metode_pembayaran === 'transfer' ? 'menunggu_pembayaran' : 'diproses'
            ]);

            if ($request->hasFile('bukti_pembayaran')) {
                $path = $request->file('bukti_pembayaran')
                    ->store('bukti_pembayaran', 'public');
                $transaksi->update(['bukti_pembayaran' => $path]);
            }

            $stok = $transaksi->stok;
            $stok->decrement('jumlah_stok', $request->kuantitas);

            // Notify owner if bank transfer
            if ($request->metode_pembayaran === 'transfer') {
                event(new \App\Events\TransaksiDibuat($transaksi));
            }

            return redirect()
                   ->route('pengepul.stok.index')
                   ->with('success',
                       $request->metode_pembayaran === 'transfer'
                       ? 'Pesanan berhasil dibuat, silahkan melakukan pembayaran'
                       : 'Pesanan berhasil dibuat'
                   );
        });
    }
}
