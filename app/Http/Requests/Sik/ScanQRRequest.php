<?php

namespace App\Http\Requests\Sik;

use Illuminate\Foundation\Http\FormRequest;

class ScanQRRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('sik.scan');
    }

    public function rules(): array
    {
        return [
            'token' => ['required', 'string'],
        ];
    }
}
