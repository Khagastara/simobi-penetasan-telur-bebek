<?php

namespace App\Http\Controllers;

use App\Models\StokDistribusi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class StokDistribusiController extends Controller
{
    public function index()
    {
        $stokDistribusi = StokDistribusi::all();
        return view('owner.stok.index', compact('stokDistribusi'));
    }

    public function create()
    {
        return view('owner.stok.create');
    }

    public function store(Request $request)
    {
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

        $gambarPath = '';
        if ($request->hasFile('gambar_stok')) {
            $gambarStok = $request->file('gambar_stok');
            $namaGambar = time() . '_' . uniqid() . '.' . $gambarStok->getClientOriginalExtension();

            $gambarPath = $gambarStok->storeAs('stok', $namaGambar, 'public');
        }

        StokDistribusi::create([
            'nama_stok' => $request->nama_stok,
            'jumlah_stok' => $request->jumlah_stok,
            'harga_stok' => $request->harga_stok,
            'deskripsi_stok' => $request->deskripsi_stok,
            'gambar_stok' => $gambarPath,
        ]);

        return redirect()->route('owner.stok.index')->with('success', 'data stok distribusi berhasil dibuat');
    }

    public function show($id)
    {
        $stok = StokDistribusi::findOrFail($id);

        if (request()->ajax()) {
            $imagePath = '';
            if ($stok->gambar_stok && Storage::disk('public')->exists($stok->gambar_stok)) {
                $imagePath = Storage::url($stok->gambar_stok);
            } else {
                $imagePath = asset('images/stok/no-image.png');
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $stok->id,
                    'nama_stok' => $stok->nama_stok,
                    'jumlah_stok' => $stok->jumlah_stok,
                    'harga_stok' => $stok->harga_stok,
                    'deskripsi_stok' => $stok->deskripsi_stok,
                    'gambar_stok' => $imagePath,
                    'created_at' => $stok->created_at,
                    'updated_at' => $stok->updated_at
                ]
            ]);
        }

        return view('owner.stok.show', compact('stok'));
    }

    public function edit($id)
    {
        $stok = StokDistribusi::findOrFail($id);
        return view('owner.stok.edit', compact('stok'));
    }

    public function update(Request $request, $id)
    {
        $stok = StokDistribusi::findOrFail($id);

        $rules = [
            'nama_stok' => 'required|string|max:255',
            'jumlah_stok' => 'required|integer',
            'harga_stok' => 'required|integer',
            'deskripsi_stok' => 'nullable|string',
        ];

        if ($request->hasFile('gambar_stok')) {
            $rules['gambar_stok'] = 'image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            if ($validator->errors()->has('jumlah_stok')) {
                return redirect()->back()->with('error', 'jumlah stok harus berisi angka')->withInput();
            }
            if ($validator->errors()->has('harga_stok')) {
                return redirect()->back()->with('error', 'harga stok harus berisi angka')->withInput();
            }

            return redirect()->back()->with('error', 'data ada yang kosong')->withInput();
        }

        $updateData = [
            'nama_stok' => $request->nama_stok,
            'jumlah_stok' => $request->jumlah_stok,
            'harga_stok' => $request->harga_stok,
            'deskripsi_stok' => $request->deskripsi_stok,
        ];

        if ($request->hasFile('gambar_stok')) {
            if ($stok->gambar_stok && Storage::disk('public')->exists($stok->gambar_stok)) {
                Storage::disk('public')->delete($stok->gambar_stok);
            }

            $gambarStok = $request->file('gambar_stok');
            $namaGambar = time() . '_' . uniqid() . '.' . $gambarStok->getClientOriginalExtension();
            $gambarPath = $gambarStok->storeAs('stok', $namaGambar, 'public');

            $updateData['gambar_stok'] = $gambarPath;
        }

        $stok->update($updateData);

        return redirect()->route('owner.stok.index')->with('success', 'data stok distribusi berhasil diubah');
    }

    public function indexPengepul()
    {
        $stokDistribusi = StokDistribusi::all();
        return view('pengepul.stok.index', compact('stokDistribusi'));
    }

    public function showPengepul($id)
    {
        $stok = StokDistribusi::findOrFail($id);

        if (request()->ajax()) {
            $imagePath = '';
            if ($stok->gambar_stok && Storage::disk('public')->exists($stok->gambar_stok)) {
                $imagePath = Storage::url($stok->gambar_stok);
            } else {
                $imagePath = asset('images/stok/no-image.png');
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $stok->id,
                    'nama_stok' => $stok->nama_stok,
                    'jumlah_stok' => $stok->jumlah_stok,
                    'harga_stok' => $stok->harga_stok,
                    'deskripsi_stok' => $stok->deskripsi_stok,
                    'gambar_stok' => $imagePath,
                    'created_at' => $stok->created_at,
                    'updated_at' => $stok->updated_at
                ]
            ]);
        }

        return view('pengepul.stok.show', compact('stok'));
    }
}
