<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request validasi untuk membuat antrian baru.
 */
class AntrianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jenis_layanan_id' => 'required|exists:jenis_layanans,id',
            'no_plat'          => 'required|string|max:15|regex:/^[A-Z0-9\s]+$/i',
        ];
    }

    public function messages(): array
    {
        return [
            'jenis_layanan_id.required' => 'Pilih jenis layanan terlebih dahulu.',
            'jenis_layanan_id.exists'   => 'Jenis layanan tidak ditemukan.',
            'no_plat.required'          => 'Nomor plat kendaraan wajib diisi.',
            'no_plat.regex'             => 'Format nomor plat tidak valid. Gunakan huruf dan angka saja.',
        ];
    }
}
