<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt([
            'username' => $request->username,
            'password' => $request->password,
        ])) {

            $akun = Auth::guard('web')->user();
            if ($akun->owner) {
                Auth::guard('owner')->login($akun->owner);
                return redirect()->route('owner.dashboard');
            }
            elseif ($akun->pengepul) {
                Auth::guard('pengepul')->login($akun->pengepul);
                return redirect()->route('pengepul.stok.index');
            }
        }

        return back()->withErrors([
            'username' => 'Username atau password salah',
        ])->withInput();
    }
}
