<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

class OwnerResetPasswordRequest extends FormRequest
{
    public function rules()
    {
        return [
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'Password baru harus diisi',
            'password.min' => 'Password harus terdiri dari minimal 6 karakter',
            'password_confirmation.required' => 'Konfirmasi password harus diisi',
            'password_confirmation.same' => 'Konfirmasi password tidak sesuai',
        ];
    }
}
