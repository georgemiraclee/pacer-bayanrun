<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CandidateRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // form publik, semua boleh submit
    }

    public function rules(): array
    {
        $rules = [
            // ── Data Wajib ──────────────────────────────────
            'email' => [
                'required',
                'email:rfc,dns',
                'unique:candidates,email',
                'max:255',
            ],
            'nama' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'tanggal_lahir' => [
                'required',
                'date',
                'before:today',
                'after:1950-01-01',
            ],
            'domisili' => [
                'required',
                'string',
                'max:255',
            ],
            'alamat' => [
                'required',
                'string',
                'min:10',
                'max:1000',
            ],
            'ktp_file' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:10240', // 10 MB
            ],
            'instagram' => [
                'required',
                'url',
                'max:255',
            ],
            'strava' => [
                'required',
                'url',
                'max:255',
            ],

            // ── Pengalaman Race ─────────────────────────────
            'is_full_marathon' => ['required', 'in:pernah,tidak'],
            'is_half_marathon' => ['required', 'in:pernah,tidak'],
        ];

        // ── Conditional: Full Marathon ──────────────────────
        if ($this->input('is_full_marathon') === 'pernah') {
            $rules['fm_event']       = ['required', 'string', 'max:255'];
            $rules['fm_year']        = ['required', 'digits:4', 'integer', 'min:2000', 'max:' . date('Y')];
            $rules['fm_certificate'] = ['required', 'file', 'mimes:jpg,jpeg,png', 'max:10240'];
        }

        // ── Conditional: Half Marathon ──────────────────────
        if ($this->input('is_half_marathon') === 'pernah') {
            $rules['hm_event']       = ['required', 'string', 'max:255'];
            $rules['hm_year']        = ['required', 'digits:4', 'integer', 'min:2000', 'max:' . date('Y')];
            $rules['hm_certificate'] = ['required', 'file', 'mimes:jpg,jpeg,png', 'max:10240'];
        }

        // ── Jika belum pernah FM maupun HM ─────────────────
        if (
            $this->input('is_full_marathon') === 'tidak' &&
            $this->input('is_half_marathon') === 'tidak'
        ) {
            $rules['pengalaman_lari'] = ['required', 'string', 'min:20', 'max:2000'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'email.required'          => 'Email wajib diisi.',
            'email.email'             => 'Format email tidak valid.',
            'email.unique'            => 'Email ini sudah terdaftar. Setiap kandidat hanya boleh mendaftar sekali.',
            'nama.required'           => 'Nama lengkap wajib diisi.',
            'nama.min'                => 'Nama minimal 3 karakter.',
            'tanggal_lahir.required'  => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before'    => 'Tanggal lahir tidak valid.',
            'domisili.required'       => 'Domisili wajib diisi.',
            'alamat.required'         => 'Alamat domisili wajib diisi.',
            'alamat.min'              => 'Alamat terlalu singkat, minimal 10 karakter.',
            'ktp_file.required'       => 'Upload KTP wajib dilakukan.',
            'ktp_file.mimes'          => 'KTP harus berformat PDF, JPG, atau PNG.',
            'ktp_file.max'            => 'Ukuran file KTP maksimal 10 MB.',
            'instagram.required'      => 'Link Instagram wajib diisi.',
            'instagram.url'           => 'Link Instagram harus berupa URL yang valid (contoh: https://instagram.com/username).',
            'strava.required'         => 'Link Strava wajib diisi.',
            'strava.url'              => 'Link Strava harus berupa URL yang valid.',
            'is_full_marathon.required' => 'Pilihan pengalaman Full Marathon wajib dipilih.',
            'is_half_marathon.required' => 'Pilihan pengalaman Half Marathon wajib dipilih.',
            'fm_event.required'       => 'Nama event Full Marathon wajib diisi.',
            'fm_year.required'        => 'Tahun event Full Marathon wajib diisi.',
            'fm_year.digits'          => 'Tahun harus 4 digit (contoh: 2023).',
            'fm_certificate.required' => 'Sertifikat Full Marathon wajib diupload.',
            'fm_certificate.mimes'    => 'Sertifikat harus berformat JPG atau PNG.',
            'hm_event.required'       => 'Nama event Half Marathon wajib diisi.',
            'hm_year.required'        => 'Tahun event Half Marathon wajib diisi.',
            'hm_certificate.required' => 'Sertifikat Half Marathon wajib diupload.',
            'hm_certificate.mimes'    => 'Sertifikat harus berformat JPG atau PNG.',
            'pengalaman_lari.required' => 'Ceritakan pengalaman lari Anda (wajib diisi jika belum pernah FM/HM).',
            'pengalaman_lari.min'     => 'Deskripsi pengalaman lari minimal 20 karakter.',
        ];
    }
}
