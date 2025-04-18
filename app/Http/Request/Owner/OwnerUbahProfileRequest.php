<?php

namespace App\Http\Requests\Owner\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OwnerUbahProfilRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $ownerId = $this->user()->id_akun;

        return [
            'nama' => 'sometimes|string',
            'no_hp' => 'sometimes|numeric',
            'email' => 'sometimes|email',
            'username' => 'sometimes|string',
            'password' => 'sometimes|string|min:6|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'no_hp.numeric' => 'Nomor HP harus berupa angka',
            'no_hp.digits_between' => 'Nomor HP harus 10-15 digit',
            'no_hp.unique' => 'Nomor HP sudah terdaftar',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'username.unique' => 'Username sudah terdaftar',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok'
        ];
    }
}
