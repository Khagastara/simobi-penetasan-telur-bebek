<?php

namespace App\Http\Requests\Owner\Auth;

use Illuminate\Foundation\Http\FormRequest;

class OwnerVerifyOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'otp' => [
                'required',
                'numeric',
                'digits:6',
                function ($attribute, $value, $fail) {
                    if (!cache()->has('otp_'.$this->email)) {
                        $fail('Kode OTP telah kadaluarsa');
                    }

                    if (cache('otp_'.$this->email) !== $value) {
                        $fail('Kode OTP tidak valid');
                    }
                }
            ],
            'email' => 'required|email'
        ];
    }

    public function messages(): array
    {
        return [
            'otp.required' => 'Kode OTP wajib diisi',
            'otp.numeric' => 'Kode OTP harus berupa angka',
            'otp.digits' => 'Kode OTP harus 6 digit'
        ];
    }
}
