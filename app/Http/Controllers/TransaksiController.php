<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Pengepul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['pengepul.akun', 'detailTransaksi.stokDistribusi'])
            ->whereHas('pengepul', function ($query) {
                $query->where('id_akun', Auth::user()->owner->id_akun);
            })
            ->get();

        dd($transaksis);
        return view('owner.transaksi.index', compact('transaksis'));
    }

    public function show($id)
    {
        $transaksi = Transaksi::with(['pengepul.akun'])->findOrFail($id);
        $pengepul = $transaksi->pengepul;

        return view('owner.transaksi.show', compact('pengepul'));
    }
}
