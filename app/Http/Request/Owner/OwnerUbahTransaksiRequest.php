<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OwnerUbahTransaksiRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->user_type === 'owner';
    }

    public function rules()
    {
        return [
            'id_transaksi' => [
                'required',
                'integer',
                'exists:transaksis,id_transaksi',
                function ($attribute, $value, $fail) {
                    if (!$this->user()->transaksis()->where('id_transaksi', $value)->exists()) {
                        $fail('Transaksi tidak valid untuk owner ini');
                    }
                }
            ],
            'status' => [
                'required',
                Rule::in(['pembayaran_valid', 'packing', 'pengiriman', 'selesai'])
            ],
            'metode_pembayaran' => [
                Rule::requiredIf(function() {
                    return $this->input('status') === 'pembayaran_valid';
                }),
                'nullable',
                Rule::in(['transfer', 'tunai'])
            ]
        ];
    }

    public function messages()
    {
        return [
            'id_transaksi.exists' => 'Transaksi tidak ditemukan',
            'id_transaksi.required' => 'ID Transaksi wajib diisi',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status harus: pembayaran_valid, packing, pengiriman, atau selesai',
            'metode_pembayaran.required' => 'Metode pembayaran wajib diisi ketika memvalidasi pembayaran',
            'metode_pembayaran.in' => 'Metode pembayaran harus: transfer atau tunai'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $currentStatus = $this->route('transaksi')->status;
            $newStatus = $this->input('status');

            $statusFlow = [
                'pending' => 0,
                'pembayaran_valid' => 1,
                'packing' => 2,
                'pengiriman' => 3,
                'selesai' => 4
            ];

            if ($statusFlow[$newStatus] < $statusFlow[$currentStatus]) {
                $validator->errors()->add(
                    'status',
                    'Tidak bisa mengubah status ke sebelumnya'
                );
            }
        });
    }
}
