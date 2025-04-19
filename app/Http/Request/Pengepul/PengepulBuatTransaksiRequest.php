<?php

namespace App\Http\Requests\Pengepul;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PengepulBuatTransaksiRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->user_type === 'pengepul';
    }

    public function rules()
    {
        return [
            'id_stok_distribusi' => [
                'required',
                'integer',
                'exists:stoks,id_stok',
                function ($attribute, $value, $fail) {
                    $stok = \App\Models\StokDistribusi::find($value);
                    if ($stok->jumlah_stok < $this->input('kuantitas')) {
                        $fail('Stok tidak mencukupi');
                    }
                }
            ],
            'kuantitas' => [
                'required',
                'integer',
                'min:1',
            ],
            'metode_pembayaran' => [
                'required',
                Rule::in(['transfer', 'tunai'])
            ],
        ];
    }

    public function messages()
    {
        return [
            'id_stok_distribusi.required' => 'Stok harus dipilih',
            'id_stok_distribusi.exists' => 'Stok tidak valid',
            'kuantitas.required' => 'Kuantitas wajib diisi',
            'kuantitas.integer' => 'Kuantitas harus berupa angka',
            'kuantitas.min' => 'Kuantitas minimal 1',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih',
            'metode_pembayaran.in' => 'Metode pembayaran harus transfer atau tunai',
            'bukti_pembayaran.required' => 'Bukti pembayaran wajib diupload untuk transfer',
            'bukti_pembayaran.image' => 'File harus berupa gambar',
            'bukti_pembayaran.max' => 'Ukuran file maksimal 2MB'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $stok = \App\Models\StokDistribusi::find($this->input('id_stok_distribusi'));
            $this->merge([
                'total' => $stok->harga_stok * $this->input('kuantitas')
            ]);
        });
    }
}
