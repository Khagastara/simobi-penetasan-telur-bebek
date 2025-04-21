<?php

namespace App\Http\Controllers\Homepage\Pengepul;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pengepul\PengepulLoginRequest;
use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengepulLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('homepage.pengepul.pages.login');
    }

    public function login(PengepulLoginRequest $request)
    {
        $credentials = $request->only('username', 'password');
        $credentials['role'] = 'pengepul';

        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard/pengepul');
        }

        $akun = Akun::where('username', $credentials['username'])
                  ->where('role', 'pengepul')
                  ->first();

        if (!$akun) {
            return back()->withErrors([
                'username' => 'Username tidak terdaftar sebagai pengepul',
            ])->onlyInput('username');
        }

        return back()->withErrors([
            'password' => 'Password salah',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
