<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

class OwnerVerifyOtpRequest extends FormRequest
{
    public function rules()
    {
        return [
            'otp' => 'required|numeric|digits:6',
        ];
    }

    public function messages()
    {
        return [
            'otp.required' => 'Kode OTP harus diisi',
            'otp.numeric' => 'Kode OTP harus berupa angka',
            'otp.digits' => 'Kode OTP harus terdiri dari 6 digit',
        ];
    }
}
