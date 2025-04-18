<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OwnerUbahPenjadwalanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $penjadwalanId = $this->route('penjadwalan');

        return [
            'tanggal' => [
                'sometimes',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) {
                    if (date('N', strtotime($value)) >= 6) {
                        $fail('Tidak bisa menjadwalkan di hari libur');
                    }
                }
            ],

            'id_status_kegiatan' => [
                'sometimes',
                Rule::exists('status_kegiatan', 'id_status_kegiatan')
                    ->whereIn('nama_status', ['Terjadwal', 'Berlangsung', 'Selesai'])
            ],

            'detail_penjadwalan' => 'sometimes|array',
            'detail_penjadwalan.*.id_detail_penjadwalan' => [
                'sometimes',
                Rule::exists('detail_penjadwalan', 'id_detail_penjadwalan')
                    ->where('id_penjadwalan_kegiatan', $penjadwalanId)
            ],
            'detail_penjadwalan.*.waktu' => 'sometimes|date_format:H:i',
            'detail_penjadwalan.*.nama_kegiatan' => 'sometimes|string|max:100',
        ];
    }

    public function messages()
    {
        return [
            'detail_penjadwalan.*.id_detail_penjadwalan.exists' => 'Detail jadwal tidak valid',
            'id_status_kegiatan.exists' => 'Status kegiatan tidak valid',
        ];
    }
}
