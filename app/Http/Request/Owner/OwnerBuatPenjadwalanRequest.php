<?php

namespace App\Http\Requests\Owner\Breeding;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OwnerBuatPenjadwalanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tanggal' => [
                'required',
                'date',
                'after_or_equal:today'
            ],
            'keterangan' => 'nullable|string|max:255',

            'detail_penjadwalan' => 'required|array',
            'detail_penjadwalan.*.waktu' => 'required|date_format:H:i',
            'detail_penjadwalan.*.nama_kegiatan' => 'required|string|max:100',

            'id_status_kegiatan' => [
                'required',
                Rule::exists('status_kegiatan', 'id_status_kegiatan')
                    ->where(function ($query) {
                        $query->whereIn('nama_status', ['Terjadwal', 'Berlangsung', 'Selesai']);
                    })
            ],
        ];
    }

    public function messages()
    {
        return [
            'tanggal.after_or_equal' => 'Tanggal harus hari ini atau setelahnya',
            'detail_penjadwalan.*.waktu.date_format' => 'Format waktu harus HH:MM',
            'id_status_kegiatan.exists' => 'Status kegiatan tidak valid',
        ];
    }
}
