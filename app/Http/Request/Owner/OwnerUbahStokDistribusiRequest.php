<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

class OwnerUbahStokDistribusiRequest extends FormRequest
{
    public function rules()
    {
        return [
            'id_stok_distribusi' => 'required',
            'nama_stok' => 'required|string|max:200',
            'jumlah_stok' => 'required|integer',
            'harga_stok' => 'required|integer',
            'deskripsi_stok' => 'nullable|string',
            'gambar_stok' => 'mimes:jpg,png|max:5120', //max 5MB
        ];
    }
}
