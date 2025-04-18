<?php

namespace App\Http\Requests\Owner\Auth;

use Illuminate\Foundation\Http\FormRequest;

class OwnerLupaPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:owners,email',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email Tidak Boleh Kosong',
            'email.email' => 'Format email tidak valid',
            'email.exists' => 'Email tidak terdaftar sebagai owner'
        ];
    }
}
