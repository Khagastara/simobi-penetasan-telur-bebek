<?php

namespace App\Http\Controllers\Dashboard\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\Auth\OwnerLupaPasswordRequest;
use App\Http\Requests\Owner\Auth\OwnerResetPasswordRequest;
use App\Http\Requests\Owner\Auth\OwnerVerifyOtpRequest;
use App\Models\Akun;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\OwnerPasswordResetOtp;

class PasswordResetController extends Controller
{
    /**
     * Show forgot password form
     */
    public function showLinkRequestForm()
    {
        return view('owner.auth.passwords.email');
    }

    /**
     * Handle password reset request
     */
    public function sendResetLinkEmail(OwnerLupaPasswordRequest $request)
    {
        $email = $request->validated()['email'];

        // Generate 6-digit OTP
        $otp = random_int(100000, 999999);

        Cache::put('otp_'.$email, $otp, now()->addMinutes(1));

        Mail::to($email)->send(new OwnerPasswordResetOtp($otp));

        return redirect()->route('owner.password.verify')
               ->with(['email' => $email, 'success' => 'Kode OTP telah dikirim ke email Anda']);
    }

    /**
     * Show OTP verification form
     */
    public function showVerifyForm()
    {
        return view('owner.auth.passwords.verify');
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(OwnerVerifyOtpRequest $request)
    {
        $email = $request->validated()['email'];

        Cache::put('otp_verified_'.$email, true, now()->addMinutes(30));

        return redirect()->route('owner.password.reset')
               ->with(['email' => $email, 'success' => 'OTP berhasil diverifikasi']);
    }

    public function showResetForm()
    {
        return view('owner.auth.passwords.reset');
    }

    public function reset(OwnerResetPasswordRequest $request)
    {
        $validated = $request->validated();

        $owner = Akun::where('email', $validated['email'])
                  ->where('user_type', 'owner')
                  ->firstOrFail();

        $owner->update([
            'password' => bcrypt($validated['password'])
        ]);

        Cache::forget('otp_'.$validated['email']);
        Cache::forget('otp_verified_'.$validated['email']);

        return redirect()->route('owner.login')
               ->with('success', 'Password berhasil diubah. Silakan login dengan password baru');
    }
}
