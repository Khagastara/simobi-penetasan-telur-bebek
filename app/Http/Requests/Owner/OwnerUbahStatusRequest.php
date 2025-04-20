<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

class OwnerUbahStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status_id' => 'required|exists:status_kegiatans,id',
        ];
    }

    public function messages(): array
    {
        return [
            'status_id.required' => 'Status kegiatan wajib dipilih.',
            'status_id.exists' => 'Status yang dipilih tidak valid.',
        ];
    }
}
