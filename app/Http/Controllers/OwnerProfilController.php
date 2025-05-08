<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Owner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class OwnerProfilController extends Controller
{
    public function show()
    {
        $owner = Auth::user()->owner;
        return view('owner.profil.show', compact('owner'));
    }

    public function edit()
    {
        $owner = Auth::user()->owner;
        return view('owner.profil.edit', compact('owner'));
    }

    public function update(Request $request)
    {
        $owner = Auth::user()->owner;

        // Validate the request
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15|unique:owners,no_hp,' . $owner->id . ',id',
            'email' => 'required|string|email|max:255|unique:akuns,email,' . $owner->akun->id . ',id',
            'username' => 'required|string|max:255|unique:akuns,username,' . $owner->akun->id . ',id',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $akun = $owner->akun;
        $akun->username = $request->username;
        $akun->email = $request->email;

        if ($request->password) {
            $akun->password = Hash::make($request->password);
        }

        $akun->save();

        $owner->nama = $request->nama;
        $owner->no_hp = $request->no_hp;
        $owner->save();

        return redirect()->route('owner.profil.show')->with('success', 'Data berhasil diubah');
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        Auth::guard('owner')->logout();

        return redirect()->route('login');
    }
}
