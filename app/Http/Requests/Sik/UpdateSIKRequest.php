<?php

namespace App\Http\Requests\Sik;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSIKRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('suratIzinKeluar'));
    }

    public function rules(): array
    {
        return [
            'jenis_izin' => ['required', Rule::in(array_keys(config('sik.jenis_izin')))],
            'keperluan' => ['required', 'string', 'max:1000'],
            'kendaraan' => ['nullable', 'string', 'max:100'],
            'catatan' => ['nullable', 'string', 'max:1000'],
            'lampiran' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'jam_keluar_rencana' => ['required', 'date'],
            'jam_kembali_rencana' => ['required', 'date', 'after:jam_keluar_rencana'],
        ];
    }
}
