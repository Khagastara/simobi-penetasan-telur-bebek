<?php

namespace App\Http\Requests\Owner\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class OwnerResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:akuns,email,user_type,owner',
            'password' => [
                'required',
                'string',
                'min:7',
                'confirmed'
            ],
            'password_confirmation' => 'required|same:password'
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'Password baru wajib diisi',
            'password.min' => 'Password minimal 7 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password_confirmation.required' => 'Harus mengisi konfirmasi password',
            'password_confirmation.same' => 'Konfirmasi password tidak sama'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!cache()->has('otp_verified_'.$this->email)) {
                $validator->errors()->add('otp', 'Verifikasi OTP diperlukan');
            }
        });
    }
}
