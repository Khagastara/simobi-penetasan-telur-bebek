<?php

namespace App\Http\Requests\Owner;

use App\Models\StatusKegiatan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OwnerBuatJadwalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled at route/middleware level
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'tgl_penjadwalan' => 'required|date',
            'nama_kegiatan' => 'required|string|max:255',
            'waktu_kegiatan' => 'required|date_format:H:i',
            'keterangan' => 'required|string',
            'id_owner' => [
                'required',
                Rule::exists('owners', 'id')->where(function ($query) {
                    $query->where('active', true);
                })
            ],
            'id_status_kegiatan' => 'sometimes|required|exists:status_kegiatans,id'
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages(): array
    {
        return [
            'tgl_penjadwalan.required' => 'Tanggal penjadwalan wajib diisi',
            'tgl_penjadwalan.date' => 'Format tanggal tidak valid',
            'nama_kegiatan.required' => 'Nama kegiatan wajib diisi',
            'waktu_kegiatan.required' => 'Waktu kegiatan wajib diisi',
            'waktu_kegiatan.date_format' => 'Format waktu harus HH:MM',
            'keterangan.required' => 'Keterangan wajib diisi',
            'id_owner.required' => 'Pemilik wajib dipilih',
            'id_owner.exists' => 'Pemilik tidak valid atau tidak aktif',
            'id_status_kegiatan.exists' => 'Status kegiatan tidak valid'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        if ($this->has('id_status_kegiatan') && empty($this->id_status_kegiatan)) {
            $this->merge([
                'id_status_kegiatan' => StatusKegiatan::where('nama_status_kgtn', 'To Do')->first()->id
            ]);
        }
    }
}
