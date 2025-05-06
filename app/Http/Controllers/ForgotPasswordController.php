<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{

    public function showForgotPasswordForm()
    {
        return view('Auth.forgot-password');
    }
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:akuns,email',
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.exists' => 'Email tidak terdaftar dalam sistem',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $otp = rand(100000, 999999);
        $email = $request->email;

        Cache::put('otp_' . $email, $otp, 600);
        Mail::to($email)->send(new OtpMail($otp));

        return redirect()->route('password.otp', ['email' => $email])
            ->with('success', 'Kode OTP telah dikirim ke email Anda');
    }

    public function showOtpForm(Request $request)
    {
        return view('Auth.otp-verification', ['email' => $request->email]);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:akuns,email',
            'otp' => 'required|numeric|digits:6',
        ], [
            'otp.required' => 'Kode OTP harus diisi',
            'otp.numeric' => 'Kode OTP harus berupa angka',
            'otp.digits' => 'Kode OTP harus terdiri dari 6 digit',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $email = $request->email;
        $otp = $request->otp;
        $cachedOtp = Cache::get('otp_' . $email);

        if (!$cachedOtp || $cachedOtp != $otp) {
            return redirect()->back()
                ->with('error', 'Kode OTP tidak valid')
                ->withInput();
        }

        return redirect()->route('password.reset', ['email' => $email, 'token' => Str::random(60)]);
    }

    public function showResetForm(Request $request)
    {
        return view('Auth.reset-password', [
            'email' => $request->email,
            'token' => $request->token,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:akuns,email',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
        ], [
            'password.required' => 'Password baru harus diisi',
            'password.min' => 'Password harus terdiri dari minimal 6 karakter',
            'password_confirmation.required' => 'Konfirmasi password harus diisi',
            'password_confirmation.same' => 'Konfirmasi password tidak sama',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('email', $request->email)
                ->with('token', $request->token);
        }

        $akun = Akun::where('email', $request->email)->first();
        $akun->password = Hash::make($request->password);
        $akun->save();

        Cache::forget('otp_' . $request->email);

        return redirect()->route('login')
            ->with('success', 'Password berhasil diubah');
    }
}
