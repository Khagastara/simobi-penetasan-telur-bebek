<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

class OwnerUbahJadwalRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tgl_penjadwalan' => 'required|date',
            'nama_kegiatan' => 'required|string|max:255',
            'waktu_kegiatan' => 'required|date_format:H:i',
            'keterangan' => 'required|string',
            'id_status_kegiatan' => 'required|exists:status_kegiatans,id'
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Data :attribute wajib diisi',
            'date' => 'Format tanggal tidak valid',
            'date_format' => 'Format waktu harus HH:MM',
            'exists' => 'Status kegiatan tidak valid'
        ];
    }
}
