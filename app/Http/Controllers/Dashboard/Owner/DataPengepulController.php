<?php

namespace App\Http\Controllers\Dashboard\Owner;

use App\Http\Controllers\Controller;
use App\Models\Pengepul;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class DataPengepulController extends Controller
{
    public function index()
    {
        $transactions = Transaksi::with([
            'pengepul.akun',
            'detailTransaksi.stokDistribusi'
        ])
        ->latest()
        ->paginate(10);

        return view('dashboard.owner.pengepul.transaksi', compact('transactions'));
    }

    public function show($pengepulId)
    {
        $pengepul = Pengepul::with('akun')
            ->findOrFail($pengepulId);

        return view('dashboard.owner.pengepul.show', compact('pengepul'));
    }
}
