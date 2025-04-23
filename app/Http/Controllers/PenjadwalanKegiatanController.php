<?php

namespace App\Http\Controllers;

use App\Models\PenjadwalanKegiatan;
use App\Models\DetailPenjadwalan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenjadwalanKegiatanController extends Controller
{
    public function index()
    {
        $owner = Auth::user()->owner;
        $penjadwalanKegiatans = $owner->penjadwalanKegiatan()->with('detailPenjadwalan')->get();
        return view('owner.penjadwalan.index', compact('penjadwalanKegiatans'));
    }

    public function create()
    {
        $statusKegiatans = \App\Models\StatusKegiatan::all();
        return view('owner.penjadwalan.create', compact('statusKegiatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl_penjadwalan' => 'required|date',
            'detail_penjadwalan.*.waktu_kegiatan' => 'required|date_format:H:i',
            'detail_penjadwalan.*.keterangan' => 'required|string|max:255',
            'detail_penjadwalan.*.id_status_kegiatan' => 'required|exists:status_kegiatans,id',
        ]);

        $penjadwalanKegiatan = PenjadwalanKegiatan::create([
            'tgl_penjadwalan' => $request->tgl_penjadwalan,
            'id_owner' => Auth::user()->owner->id,
        ]);

        foreach ($request->detail_penjadwalan as $detail) {
            DetailPenjadwalan::create([
                'waktu_kegiatan' => $detail['waktu_kegiatan'],
                'keterangan' => $detail['keterangan'],
                'id_penjadwalan' => $penjadwalanKegiatan->id,
                'id_status_kegiatan' => $detail['id_status_kegiatan'],
            ]);
        }

        return redirect()->route('owner.penjadwalan.index')->with('success', 'Jadwal berhasil dibuat');
    }

    public function edit($id)
    {
        $penjadwalanKegiatan = PenjadwalanKegiatan::with('detailPenjadwalan')->findOrFail($id);
        return view('owner.penjadwalan.edit', compact('penjadwalanKegiatan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tgl_penjadwalan' => 'required|date',
            'detail_penjadwalan.*.waktu_kegiatan' => 'required|date_format:H:i',
            'detail_penjadwalan.*.keterangan' => 'required|string|max:255',
            'detail_penjadwalan.*.id_status_kegiatan' => 'required|exists:status_kegiatans,id',
        ]);

        $penjadwalanKegiatan = PenjadwalanKegiatan::findOrFail($id);
        $penjadwalanKegiatan->tgl_penjadwalan = $request->tgl_penjadwalan;
        $penjadwalanKegiatan->save();

        foreach ($request->detail_penjadwalan as $detail) {
            $detailPenjadwalan = DetailPenjadwalan::findOrFail($detail['id']);
            $detailPenjadwalan->waktu_kegiatan = $detail['waktu_kegiatan'];
            $detailPenjadwalan->keterangan = $detail['keterangan'];
            $detailPenjadwalan->id_status_kegiatan = $detail['id_status_kegiatan'];
            $detailPenjadwalan->save();
        }

        return redirect()->route('penjadwalan.index')->with('success', 'Jadwal berhasil diubah');
    }
}
