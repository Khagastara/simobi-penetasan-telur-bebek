<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class OwnerUbahProfilRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $user = Auth::guard('web')->user();
        $owner = $user->owner;

        return [
            'nama' => 'required|string|max:255',
            'no_hp' => [
                'required',
                'string',
                'max:15',
                Rule::unique('owners', 'no_hp')->ignore($owner->id)
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('akuns', 'email')->ignore($user->id)
            ],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('akuns', 'username')->ignore($user->id)
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
            'password.min' => 'Password harus terdiri dari minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sesuai'
        ];
    }
}
