<?php

namespace App\Http\Controllers\Homepage\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\OwnerLoginRequest;
use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.owner.login');
    }

    public function login(OwnerLoginRequest $request)
    {
        $credentials = $request->only('username', 'password');
        $credentials['role'] = 'owner';

        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard/owner');
        }

        $akun = Akun::where('username', $credentials['username'])
                  ->where('role', 'owner')
                  ->first();

        if (!$akun) {
            return back()->withErrors([
                'username' => 'Username tidak terdaftar sebagai owner',
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
