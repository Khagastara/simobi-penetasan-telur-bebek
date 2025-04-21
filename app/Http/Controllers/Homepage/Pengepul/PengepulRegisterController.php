<?php

namespace App\Http\Controllers\Homepage\Pengepul;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pengepul\PengepulRegisterRequest;
use App\Models\Akun;
use App\Models\Pengepul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PengepulRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.pengepul.register');
    }

    public function register(PengepulRegisterRequest $request)
    {
        $akun = Akun::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Pengepul::create([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'id_akun' => $akun->id
        ]);

        return redirect()->route('pengepul.login')
            ->with('success', 'Akun berhasil dibuat. Silakan login.');
    }
}
