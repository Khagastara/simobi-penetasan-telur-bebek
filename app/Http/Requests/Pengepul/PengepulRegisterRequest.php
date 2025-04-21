<?php

namespace App\Http\Requests\Pengepul;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PengepulRegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|unique:pengepuls,no_hp',
            'email' => 'required|email|unique:akuns',
            'username' => 'required|string|max:255|unique:akuns',
            'password' => 'required|string|confirmed'
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Data :attribute wajib diisi',
            'no_hp.unique' => 'Nomor HP telah terdaftar',
            'email.unique' => 'Email telah terdaftar',
            'username.unique' => 'Username telah terdaftar',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sesuai'
        ];
    }
}
