<?php

namespace App\Http\Controllers;

use App\Models\StokDistribusi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class StokDistribusiController extends Controller
{
    /**
     * Display a listing of stok distribusi.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stokDistribusi = StokDistribusi::all();
        return view('owner.stok.index', compact('stokDistribusi'));
    }

    /**
     * Show the form for creating a new stok distribusi.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('owner.stok.create');
    }

    /**
     * Store a newly created stok distribusi in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi data input
        $validator = Validator::make($request->all(), [
            'nama_stok' => 'required|string|max:255',
            'jumlah_stok' => 'required|integer',
            'harga_stok' => 'required|integer',
            'deskripsi_stok' => 'nullable|string',
            'gambar_stok' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            if ($validator->errors()->has('jumlah_stok')) {
                return redirect()->back()->with('error', 'jumlah stok harus berisi angka')->withInput();
            }
            if ($validator->errors()->has('harga_stok')) {
                return redirect()->back()->with('error', 'harga stok harus berisi angka')->withInput();
            }

            return redirect()->back()->with('error', 'data ada yang kosong')->withInput();
        }

        if ($request->hasFile('gambar_stok')) {
            $gambarStok = $request->file('gambar_stok');
            $namaGambar = time() . '.' . $gambarStok->getClientOriginalExtension();
            $gambarStok->storeAs('public/images/stok', $namaGambar);
            $gambarPath = 'images/stok/' . $namaGambar;
        }

        // Simpan data stok distribusi
        StokDistribusi::create([
            'nama_stok' => $request->nama_stok,
            'jumlah_stok' => $request->jumlah_stok,
            'harga_stok' => $request->harga_stok,
            'deskripsi_stok' => $request->deskripsi_stok,
            'gambar_stok' => $gambarPath ?? '',
        ]);

        return redirect()->route('owner.stok.index')->with('success', 'data stok distribusi berhasil dibuat');
    }

    /**
     * Display the specified stok distribusi.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $stok = StokDistribusi::findOrFail($id);
        return view('owner.stok.show', compact('stok'));
    }

    /**
     * Show the form for editing the specified stok distribusi.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $stok = StokDistribusi::findOrFail($id);
        return view('owner.stok.edit', compact('stok'));
    }

    /**
     * Update the specified stok distribusi in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $stok = StokDistribusi::findOrFail($id);

        // Validasi data input
        $rules = [
            'nama_stok' => 'required|string|max:255',
            'jumlah_stok' => 'required|integer',
            'harga_stok' => 'required|integer',
            'deskripsi_stok' => 'nullable|string',
        ];

        // Jika ada gambar baru yang diupload
        if ($request->hasFile('gambar_stok')) {
            $rules['gambar_stok'] = 'image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        $validator = Validator::make($request->all(), $rules);

        // Jika validasi gagal, kembali ke form dengan pesan error
        if ($validator->fails()) {
            if ($validator->errors()->has('jumlah_stok')) {
                return redirect()->back()->with('error', 'jumlah stok harus berisi angka')->withInput();
            }
            if ($validator->errors()->has('harga_stok')) {
                return redirect()->back()->with('error', 'harga stok harus berisi angka')->withInput();
            }

            return redirect()->back()->with('error', 'data ada yang kosong')->withInput();
        }

        // Proses upload gambar baru jika ada
        if ($request->hasFile('gambar_stok')) {
            // Hapus gambar lama jika ada
            if ($stok->gambar_stok && Storage::exists('public/' . $stok->gambar_stok)) {
                Storage::delete('public/images' . $stok->gambar_stok);
            }

            // Upload gambar baru
            $gambarStok = $request->file('gambar_stok');
            $namaGambar = time() . '.' . $gambarStok->getClientOriginalExtension();
            $gambarStok->storeAs('public/images//stok', $namaGambar);
            $gambarPath = 'images/stok/' . $namaGambar;

            // Update data dengan gambar baru
            $stok->update([
                'nama_stok' => $request->nama_stok,
                'jumlah_stok' => $request->jumlah_stok,
                'harga_stok' => $request->harga_stok,
                'deskripsi_stok' => $request->deskripsi_stok,
                'gambar_stok' => $gambarPath,
            ]);
        } else {
            // Update data tanpa mengubah gambar
            $stok->update([
                'nama_stok' => $request->nama_stok,
                'jumlah_stok' => $request->jumlah_stok,
                'harga_stok' => $request->harga_stok,
                'deskripsi_stok' => $request->deskripsi_stok,
            ]);
        }

        return redirect()->route('owner.stok.show', $stok->id)->with('success', 'data stok distribusi berhasil diubah');
    }

    /**
     * Display a listing of stok distribusi for Pengepul.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexPengepul()
    {
        $stokDistribusi = StokDistribusi::all();
        return view('pengepul.stok.index', compact('stokDistribusi'));
    }

    /**
     * Display the specified stok distribusi for Pengepul.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showPengepul($id)
    {
        $stok = StokDistribusi::findOrFail($id);
        return view('pengepul.stok.show', compact('stok'));
    }
}
