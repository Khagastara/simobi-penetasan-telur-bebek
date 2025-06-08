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
        return view('auth.forgot-password', ['step' => 'email']);
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
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $lastSent = Cache::get('otp_last_sent_' . $request->email);
        if ($lastSent && now()->diffInSeconds($lastSent) < 60) {
            $remainingTime = 60 - now()->diffInSeconds($lastSent);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Tunggu {$remainingTime} detik sebelum mengirim ulang kode OTP"
                ]);
            }

            return redirect()->back()
                ->with('error', "Tunggu {$remainingTime} detik sebelum mengirim ulang kode OTP")
                ->withInput();
        }

        $otp = rand(100000, 999999);
        $email = $request->email;

        Cache::put('otp_' . $email, $otp, 600);
        Cache::put('otp_last_sent_' . $email, now(), 600);

        try {
            Mail::to($email)->send(new OtpMail($otp));

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kode OTP telah dikirim ke email Anda'
                ]);
            }

            return view('auth.forgot-password', [
                'step' => 'otp',
                'email' => $email
            ])->with('success', 'Kode OTP telah dikirim ke email Anda');

        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim email. Silakan coba lagi.'
                ]);
            }

            return redirect()->back()
                ->with('error', 'Gagal mengirim email. Silakan coba lagi.')
                ->withInput();
        }
    }

    public function showOtpForm(Request $request)
    {
        return view('auth.forgot-password', [
            'step' => 'otp',
            'email' => $request->email
        ]);
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
                ->with('error', 'Kode OTP tidak valid atau sudah kedaluwarsa')
                ->withInput();
        }

        $token = Str::random(60);

        return view('auth.forgot-password', [
            'step' => 'reset',
            'email' => $email,
            'token' => $token
        ]);
    }

    public function showResetForm(Request $request)
    {
        return view('auth.forgot-password', [
            'step' => 'reset',
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
                ->withInput();
        }

        $akun = Akun::where('email', $request->email)->first();
        $akun->password = Hash::make($request->password);
        $akun->save();

        Cache::forget('otp_' . $request->email);
        Cache::forget('otp_last_sent_' . $request->email);

        return redirect()->route('login')
            ->with('success', 'Password berhasil diubah. Silakan login dengan password baru Anda.');
    }
}
