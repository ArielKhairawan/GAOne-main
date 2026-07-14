<?php

namespace App\Http\Requests\Sik;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSIKRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('sik.create');
    }

    /**
     * Nama, Nomor Karyawan, dan Departemen SENGAJA tidak ada di sini —
     * data tersebut diambil otomatis dari akun login pada Service, bukan
     * dari input pengguna (lihat SIKController@store & SIKService@create).
     */
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

    public function messages(): array
    {
        return [
            'jam_kembali_rencana.after' => 'Jam kembali rencana harus setelah jam keluar rencana.',
        ];
    }
}
