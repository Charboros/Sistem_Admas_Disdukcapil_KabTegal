<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAduanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kanal'          => 'required|string',
            'klasifikasi'    => 'required|string',
            'isi_aduan'      => 'required|string',

            'tanggal_aduan'  => 'required|date',
            'waktu_aduan'    => 'nullable|date_format:H:i',
            'screenshot'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }
}
