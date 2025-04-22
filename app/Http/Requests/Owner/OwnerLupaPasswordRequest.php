<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

class OwnerLupaPasswordRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email|exists:akuns,email',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.exists' => 'Email tidak terdaftar',
        ];
    }
}
