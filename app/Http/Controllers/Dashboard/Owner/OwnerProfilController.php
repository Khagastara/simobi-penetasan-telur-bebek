<?php

namespace App\Http\Controllers\Dashboard\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\OwnerUbahProfilRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OwnerProfilController extends Controller
{
    public function show()
    {
        $user = Auth::guard('web')->user();
        $owner = $user->owner;

        if (!$owner) {
            abort(404, 'Owner profile not found');
        }

        return view('dashboard.owner.profile.show', [
            'owner' => $owner,
            'akun' => $user
        ]);
    }

    public function edit()
    {
        $user = Auth::guard('web')->user();
        $owner = $user->owner;

        return view('dashboard.owner.profile.edit', [
            'owner' => $owner,
            'akun' => $user
        ]);
    }


    public function update(OwnerUbahProfilRequest $request)
    {
        $user = dd(Auth::guard('web')->user());
        $owner = $user->owner;
        $owner->update([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp
        ]);

        $accountData = [
            'email' => $request->email,
            'username' => $request->username
        ];

        if ($request->filled('password')) {
            $accountData['password'] = Hash::make($request->password);
        }

        $user->update($accountData);

        return redirect()->route('dashboard.owner.profile.show')
            ->with('success', 'Data berhasil diubah');
    }
}
