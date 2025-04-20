<?php

namespace App\Http\Requests\Pengepul;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PengepulUbahProfilRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $user = Auth::guard('web')->user();
        $pengepul = $user->pengepul;

        return [
            'nama' => 'required|string|max:255',
            'no_hp' => [
                'required',
                'string',
                'max:15',
                Rule::unique('owners', 'no_hp')->ignore($pengepul->id)
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('akuns', 'email')->ignore($pengepul->id)
            ],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('akuns', 'username')->ignore($pengepul->id)
            ],
            'password' => 'nullable|string|min:6|confirmed'
        ];
    }

    public function messages()
    {
        return [
            'no_hp.unique' => 'Nomor HP telah terdaftar',
            'email.unique' => 'Email telah terdaftar',
            'username.unique' => 'Username telah terdaftar',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sesuai'
        ];
    }
}
