<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Pengepul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PengepulProfilController extends Controller
{
    public function show()
    {
        $pengepul = Auth::user()->pengepul;
        return view('pengepul.profil.show', compact('pengepul'));
    }

    public function edit()
    {
        $pengepul = Auth::user()->pengepul;
        return view('pengepul.profil.edit', compact('pengepul'));
    }

    public function update(Request $request)
    {
        $pengepul = Auth::user()->pengepul;

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15|unique:owners,no_hp,' . $pengepul->id . ',id',
            'email' => 'required|string|email|max:255|unique:akuns,email,' . $pengepul->akun->id . ',id',
            'username' => 'required|string|max:255|unique:akuns,username,' . $pengepul->akun->id . ',id',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $akun = $pengepul->akun;
        $akun->username = $request->username;
        $akun->email = $request->email;

        if ($request->password) {
            $akun->password = Hash::make($request->password);
        }

        $akun->save();

        $pengepul->nama = $request->nama;
        $pengepul->no_hp = $request->no_hp;
        $pengepul->save();

        return redirect()->route('pengepul.profil.show')->with('success', 'Data berhasil diubah');
    }
}
