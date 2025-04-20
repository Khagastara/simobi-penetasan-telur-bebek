<?php

namespace App\Http\Controllers\Dashboard\Owner;

use App\Http\Controllers\Controller;
use App\Models\PenjadwalanKegiatan;
use App\Models\DetailPenjadwalan;
use App\Models\StatusKegiatan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PenjadwalanController extends Controller
{
    // [Previous methods here...]

    // Step g-i: Show edit form
    public function edit($id)
    {
        $detail = DetailPenjadwalan::with(['penjadwalan', 'status'])
            ->findOrFail($id);

        return view('dashboard.penjadwalan.edit', [
            'detail' => $detail,
            'statusOptions' => StatusKegiatan::all(),
            'scheduleDates' => PenjadwalanKegiatan::pluck('tgl_penjadwalan')->unique()
        ]);
    }

    // Step j-l: Handle update
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tgl_penjadwalan' => 'required|date',
            'waktu_kegiatan' => 'required|date_format:H:i',
            'keterangan' => 'required|string|max:255',
            'nama_kegiatan' => 'required|string|max:255',
            'id_status_kegiatan' => 'required|exists:status_kegiatans,id'
        ]);

        $detail = DetailPenjadwalan::findOrFail($id);

        // Update parent schedule date if changed
        if ($detail->penjadwalan->tgl_penjadwalan != $validated['tgl_penjadwalan']) {
            $penjadwalan = PenjadwalanKegiatan::updateOrCreate([
                'tgl_penjadwalan' => $validated['tgl_penjadwalan'],
                'id_owner' => auth('akun')->id()
            ]);

            $detail->id_penjadwalan = $penjadwalan->id;
        }

        $detail->update([
            'waktu_kegiatan' => $validated['waktu_kegiatan'],
            'keterangan' => $validated['keterangan'],
            'nama_kegiatan' => $validated['nama_kegiatan'],
            'id_status_kegiatan' => $validated['id_status_kegiatan']
        ]);

        return redirect()->route('dashboard.penjadwalan.show', $detail->penjadwalan->tgl_penjadwalan)
            ->with('success', 'Jadwal berhasil diupdate');
    }

    // Show single detail
    public function showDetail($id)
    {
        $detail = DetailPenjadwalan::with(['penjadwalan.owner', 'status'])
            ->findOrFail($id);

        return view('dashboard.penjadwalan.show-detail', [
            'detail' => $detail
        ]);
    }
}
