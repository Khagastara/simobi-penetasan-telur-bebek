<?php

namespace App\Http\Controllers\Dashboard\Pengepul;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pengepul\PengepulRegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PengepulProfilController extends Controller
{
    public function show()
    {
        $pengepul = Auth::user()->pengepul;
        return view('dashboard.pengepul.profile.show', compact('pengepul'));
    }

    public function edit()
    {
        $pengepul = Auth::user()->pengepul;
        return view('dashboard.pengepul.profile.edit', compact('pengepul'));
    }

    public function update(PengepulRegisterRequest $request)
    {
        $user = dd(Auth::guard('web')->user());
        $pengepul = $user->pengepul;

        $pengepul->update([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp
        ]);

        $updateData = [
            'email' => $request->email,
            'username' => $request->username
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('dashboard.pengepul.profile.show')
            ->with('success', 'Data berhasil diubah');
    }
}
