<?php

namespace App\Http\Controllers\Homepage\Owner;

use App\Models\Akun;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Owner\OwnerLupaPasswordRequest;
use App\Http\Requests\Owner\OwnerVerifyOtpRequest;
use App\Http\Requests\Owner\OwnerResetPasswordRequest;

class OwnerPasswordResetController extends Controller
{
    public function showForgotPasswordForm()
    {
        return view('homepage.pages.owner.forgot-password');
    }

    public function sendOtp(OwnerLupaPasswordRequest $request)
    {
        $email = $request->email;
        $otp = rand(100000, 999999);

        // Store OTP in cache for 10 minutes
        Cache::put('otp_' . $email, $otp, now()->addMinutes(10));

        // Send OTP via email
        Mail::to($email)->send(new OtpMail($otp));

        return redirect()->route('verify.otp.form')->with(['email' => $email]);
    }

    public function showVerifyOtpForm()
    {
        return view('homepage.pages.owner.verify-otp');
    }

    public function verifyOtp(OwnerVerifyOtpRequest $request)
    {
        $email = $request->session()->get('email');
        $otp = $request->otp;

        $cachedOtp = Cache::get('otp_' . $email);

        if ($cachedOtp != $otp) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid']);
        }

        // OTP is valid, allow password reset
        Cache::forget('otp_' . $email);
        Cache::put('reset_token_' . $email, Str::random(60), now()->addMinutes(10));

        return redirect()->route('reset.password.form')->with(['email' => $email]);
    }

    public function showResetPasswordForm()
    {
        return view('homepage.pages.owner.reset-password');
    }

    public function resetPassword(OwnerResetPasswordRequest $request)
    {
        $email = $request->session()->get('email');

        if (!Cache::has('reset_token_' . $email)) {
            return redirect()->route('login')->with('error', 'Sesi reset password telah kadaluarsa');
        }

        $akun = Akun::where('email', $email)->first();
        $akun->password = Hash::make($request->password);
        $akun->save();

        Cache::forget('reset_token_' . $email);

        return redirect()->route('login')->with('success', 'Password berhasil diubah. Silakan login dengan password baru.');
    }
}
