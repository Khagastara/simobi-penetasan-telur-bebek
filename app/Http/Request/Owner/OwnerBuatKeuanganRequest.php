<?php

namespace App\Http\Requests\Owner\Keuangan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OwnerBuatKeuanganRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->user_type === 'owner';
    }

    public function rules()
    {
        return [
            'tanggal_rekapitulasi' => [
                'required',
                'date',
                'before_or_equal:today',
                function ($attribute, $value, $fail) {
                    if (\App\Models\Keuangan::whereDate('tanggal_rekapitulasi', $value)
                        ->where('id_owner', $this->user()->id_owner)
                        ->exists()) {
                        $fail('Sudah ada rekapitulasi untuk tanggal ini');
                    }
                }
            ],
            'saldo_pemasukan' => 'required|integer|min:0',
            'saldo_pengeluaran' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'tanggal_rekapitulasi.required' => 'Tanggal rekapitulasi wajib diisi',
            'tanggal_rekapitulasi.before_or_equal' => 'Tanggal tidak boleh melebihi hari ini',
            'saldo_pengeluaran.required' => 'Saldo pengeluaran wajib diisi',
            'saldo_pemasukkan.integer' => 'Saldo harus berupa angka',
            'saldo_pemasukkan.min' => 'Saldo tidak boleh negatif',
            'saldo_pengeluaran.integer' => 'Saldo harus berupa angka',
            'saldo_pengeluaran.min' => 'Saldo tidak boleh negatif',
        ];
    }

    public function prepareForValidation()
    {
        if ($this->has('saldo_pemasukkan')) {
            $this->merge([
                'saldo_pengeluaran' => str_replace('.', '', $this->saldo_pengeluaran)
            ]);
        }
        if ($this->has('saldo_pengeluaran')) {
            $this->merge([
                'saldo_pengeluaran' => str_replace('.', '', $this->saldo_pengeluaran)
            ]);
        }
    }
}
