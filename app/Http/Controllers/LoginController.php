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

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'username' => 'required|string',
    //         'password' => 'required|string',
    //     ]);

    //     // Attempt to find the user by username
    //     $akun = Akun::where('username', $request->username)->first();

    //     if (!$akun) {
    //         return back()->withErrors(['username' => 'Username tidak terdaftar']);
    //     }

    //     // Check if the password is correct
    //     if (!password_verify($request->password, $akun->password)) {
    //         return back()->withErrors(['password' => 'Password salah']);
    //     }

    //     // Log the user in
    //     Auth::login($akun);

    //     // Redirect based on user type
    //     if ($akun->owner) {
    //         return redirect()->route('owner.dashboard'); // Define this route
    //     } elseif ($akun->pengepul) {
    //         return redirect()->route('pengepul.dashboard'); // Define this route
    //     }

    //     return redirect()->route('home'); // Default route
    // }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Try to log in using Laravel's Auth system
        if (Auth::attempt([
            'username' => $request->username,
            'password' => $request->password,
        ])) {
            // Redirect based on user type
            $akun = Auth::user();
            if ($akun->owner) {
                if (Auth::guard('owner')->attempt([
                    'username' => $request->username,
                    'password' => $request->password
                ])) {
                    return redirect()->route('owner.dashboard');
                }
            }
            elseif ($akun->pengepul) {
                if (Auth::guard('pengepul')->attempt([
                    'username' => $request->username,
                    'password' => $request->password
                ])) {
                    return redirect()->route('pengepul.dashboard');
                }
            }
        }

        // If login fails
        return back()->withErrors([
            'username' => 'Username atau password salah',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login'); // Redirect to login page
    }
}
