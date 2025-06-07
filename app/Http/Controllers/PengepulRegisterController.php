<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Pengepul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PengepulRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('pengepul.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15|unique:pengepuls,no_hp',
            'email' => 'required|string|email|max:255|unique:akuns,email',
            'username' => 'required|string|max:255|unique:akuns,username',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $akun = Akun::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Pengepul::create([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'id_akun' => $akun->id,
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat. Silakan login.');
    }
}
