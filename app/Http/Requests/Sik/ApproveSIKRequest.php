<?php

namespace App\Http\Requests\Sik;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApproveSIKRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('approve', $this->route('suratIzinKeluar'));
    }

    public function rules(): array
    {
        return [
            'action' => ['required', Rule::in(['approve', 'reject'])],
            'catatan' => ['nullable', 'string', 'max:1000', 'required_if:action,reject'],
        ];
    }

    public function messages(): array
    {
        return [
            'catatan.required_if' => 'Catatan wajib diisi saat menolak pengajuan.',
        ];
    }
}
