<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CandidateRegistrationRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $rules = [
            // ── Section 1: Data Pribadi ─────────────────────────────
            'email'         => ['required','email:rfc,dns','unique:candidates,email','max:255'],
            'no_hp'              => ['required','string','min:9','max:20','regex:/^[0-9+\-\s()]+$/'],
            'nik'           => ['nullable','string','digits:16'],
            'nama'          => ['required','string','min:3','max:255'],
            'tanggal_lahir' => ['required','string','max:20'], // format DD-MM-YYYY dari OCR
            'domisili'      => ['required','string','max:255'],
            'alamat'        => ['required','string','min:10','max:1000'],
            'ktp_file'      => ['required','file','mimes:pdf,jpg,jpeg,png','max:10240'],
            'instagram'     => ['required','url','max:255'],
            'strava'        => ['required','url','max:255'],

            // ── Section 2: Full Marathon ────────────────────────────
            'is_full_marathon' => ['required','in:pernah,tidak'],

            // ── Section 3: Half Marathon ────────────────────────────
            'is_half_marathon' => ['required','in:pernah,tidak'],

            // ── Section 4: 10K ──────────────────────────────────────
            'is_10k' => ['required','in:pernah,tidak,skip'],

            // ── Section 5: 5K ───────────────────────────────────────
            'is_5k' => ['required','in:pernah,tidak,skip'],

            // ── Section 7: Mileage (semua wajib) ───────────────────
            // Section 7: Mileage
            'mileage_dec_2025'  => ['required', 'numeric', 'min:0'],
            'mileage_dec_graph' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:10240'], // Ganti ke nullable

            'mileage_jan_2026'  => ['required', 'numeric', 'min:0'],
            'mileage_jan_graph' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:10240'], // Ganti ke nullable

            'mileage_feb_2026'  => ['required', 'numeric', 'min:0'],
            'mileage_feb_graph' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:10240'], // Ganti ke nullable

            'mileage_mar_2026'  => ['required', 'numeric', 'min:0'],
            'mileage_mar_graph' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:10240'], // Ganti ke nullable

            // ── Section 9: Pengalaman Pacer ─────────────────────────
            'is_pacer_experience' => ['required','in:pernah,tidak'],

            // ── Section 10: Essay ───────────────────────────────────
            'essay_running_world'    => ['required','string','min:50','max:3000'],
            'essay_pacer_definition' => ['required','string','min:30','max:3000'],

            // ── Section 11: Komitmen ────────────────────────────────
            'alasan_pantas'      => ['required','string','min:30','max:3000'],
            'preferred_distance' => ['required','array','min:1'],
            'preferred_distance.*'=> ['in:hm,10k,5k,any'],
            'komitmen'           => ['required','in:ya_siap,tidak_siap,mencoba_menyesuaikan'],
            'izin_keluarga'      => ['required','in:ya,belum'],

            // ── Section 12: Dokumen Final ───────────────────────────
            'waiver_file'            => ['required','file','mimes:pdf,jpg,jpeg,png','max:10240'],
            'pernyataan_keabsahan'   => ['required','accepted'],
        ];

        // ── Conditional: Full Marathon ──────────────────────────────
        if ($this->is_full_marathon === 'pernah') {
            $rules['fm_event'] = ['required', 'string', 'max:255'];
            $rules['fm_year'] = ['required', 'numeric', 'min:2010', 'max:'.date('Y')];
            $rules['fm_certificate'] = ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'];
        }

        // ── Conditional: Half Marathon ──────────────────────────────
        if ($this->input('is_half_marathon') === 'pernah') {
            $rules['hm_event']       = ['required','string','max:255'];
            $rules['hm_year']        = ['required','digits:4','integer','min:2000','max:'.date('Y')];
            $rules['hm_certificate'] = ['required','file','mimes:jpg,jpeg,png','max:10240'];
        }

        // ── Conditional: 10K ────────────────────────────────────────
        if ($this->input('is_10k') === 'pernah') {
            $rules['race_10k_event'] = ['required','string','max:255'];
            $rules['race_10k_year']  = ['required','digits:4','integer','min:2000','max:'.date('Y')];
            // sertifikat 10K opsional (jika ada)
            $rules['race_10k_certificate'] = ['nullable','file','mimes:jpg,jpeg,png','max:10240'];
        }

        // ── Conditional: 5K ─────────────────────────────────────────
        if ($this->input('is_5k') === 'pernah') {
            $rules['race_5k_event'] = ['required','string','max:255'];
            $rules['race_5k_year']  = ['required','digits:4','integer','min:2000','max:'.date('Y')];
            $rules['race_5k_certificate'] = ['nullable','file','mimes:jpg,jpeg,png','max:10240'];
        }

        // ── Conditional: Trail/Non-Road ─────────────────────────────
        if ($this->input('trail_status') === 'trail') {
            $rules['trail_event']       = ['required','string','max:255'];
            $rules['trail_year']        = ['required','digits:4','integer','min:2000','max:'.date('Y')];
            $rules['trail_certificate'] = ['required','file','mimes:jpg,jpeg,png','max:10240'];
        }

        // ── Conditional: Best Time Files (opsional, hanya jika ada best time) ──
        $rules['best_time_fm']       = ['nullable','string','max:20'];
        $rules['best_time_fm_file']  = ['nullable','file','mimes:jpg,jpeg,png','max:10240'];
        $rules['best_time_hm']       = ['nullable','string','max:20'];
        $rules['best_time_hm_file']  = ['nullable','file','mimes:jpg,jpeg,png','max:10240'];
        $rules['best_time_10k']      = ['nullable','string','max:20'];
        $rules['best_time_10k_file'] = ['nullable','file','mimes:jpg,jpeg,png','max:10240'];
        $rules['best_time_5k']       = ['nullable','string','max:20'];
        $rules['best_time_5k_file']  = ['nullable','file','mimes:jpg,jpeg,png','max:10240'];

        // ── Conditional: Pacer Experience ───────────────────────────
        if ($this->input('is_pacer_experience') === 'pernah') {
            $rules['pacer_event_list']     = ['required','string','min:5','max:1000'];
            $rules['pacer_distance_pace']  = ['required','string','min:5','max:1000'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            // Data Pribadi
            'email.required'          => 'Email wajib diisi.',
            'email.email'             => 'Format email tidak valid.',
            'email.unique'            => 'Email ini sudah terdaftar.',
           'no_hp.required'          => 'Nomor WhatsApp/telepon wajib diisi.',
            'no_hp.min'               => 'Nomor telepon minimal 9 digit.',
            'no_hp.regex'             => 'Format nomor telepon tidak valid.',
            'nama.required'           => 'Nama lengkap wajib diisi.',
            'tanggal_lahir.required'  => 'Tanggal lahir wajib diisi.',
            'domisili.required'       => 'Domisili wajib diisi.',
            'alamat.required'         => 'Alamat lengkap wajib diisi.',
            'ktp_file.required'       => 'Upload KTP wajib dilakukan.',
            'ktp_file.mimes'          => 'KTP harus berformat PDF, JPG, atau PNG.',
            'ktp_file.max'            => 'Ukuran file KTP maksimal 10 MB.',
            'instagram.required'      => 'Link Instagram wajib diisi.',
            'instagram.url'           => 'Link Instagram harus berupa URL valid.',
            'strava.required'         => 'Link Strava wajib diisi.',
            'strava.url'              => 'Link Strava harus berupa URL valid.',
            // Race
            'is_full_marathon.required' => 'Pilihan pengalaman Full Marathon wajib dipilih.',
            'is_half_marathon.required' => 'Pilihan pengalaman Half Marathon wajib dipilih.',
            'is_10k.required'           => 'Pilihan pengalaman 10K wajib dipilih.',
            'is_5k.required'            => 'Pilihan pengalaman 5K wajib dipilih.',
            'fm_event.required'         => 'Nama event FM wajib diisi.',
            'fm_year.required'          => 'Tahun FM wajib diisi.',
            'fm_certificate.required'   => 'Sertifikat FM wajib diupload.',
            'hm_event.required'         => 'Nama event HM wajib diisi.',
            'hm_year.required'          => 'Tahun HM wajib diisi.',
            'hm_certificate.required'   => 'Sertifikat HM wajib diupload.',
            'race_10k_event.required'   => 'Nama event 10K wajib diisi.',
            'race_10k_year.required'    => 'Tahun 10K wajib diisi.',
            'race_5k_event.required'    => 'Nama event 5K wajib diisi.',
            'race_5k_year.required'     => 'Tahun 5K wajib diisi.',
            // Mileage
            'mileage_dec_2025.required'  => 'Total mileage Desember 2025 wajib diisi.',
            'mileage_dec_graph.required' => 'Grafik mileage Desember 2025 wajib diupload.',
            'mileage_jan_2026.required'  => 'Total mileage Januari 2026 wajib diisi.',
            'mileage_jan_graph.required' => 'Grafik mileage Januari 2026 wajib diupload.',
            'mileage_feb_2026.required'  => 'Total mileage Februari 2026 wajib diisi.',
            'mileage_feb_graph.required' => 'Grafik mileage Februari 2026 wajib diupload.',
            'mileage_mar_2026.required'  => 'Total mileage Maret 2026 wajib diisi.',
            'mileage_mar_graph.required' => 'Grafik mileage Maret 2026 wajib diupload.',
            // Essay
            'essay_running_world.required'    => 'Pandangan tentang dunia lari wajib diisi.',
            'essay_running_world.min'         => 'Jawaban minimal 50 karakter.',
            'essay_pacer_definition.required' => 'Penjelasan tentang pacer wajib diisi.',
            'essay_pacer_definition.min'      => 'Jawaban minimal 30 karakter.',
            // Komitmen
            'alasan_pantas.required'      => 'Alasan mengapa pantas jadi pacer wajib diisi.',
            'alasan_pantas.min'           => 'Jawaban minimal 30 karakter.',
            'preferred_distance.required' => 'Pilih minimal satu jarak favorit.',
            'komitmen.required'           => 'Pilihan komitmen wajib dipilih.',
            'izin_keluarga.required'      => 'Pernyataan izin keluarga wajib dipilih.',
            // Dokumen Final
            'waiver_file.required'          => 'Surat Waiver wajib diupload.',
            'waiver_file.mimes'             => 'Waiver harus berformat PDF, JPG, atau PNG.',
            'pernyataan_keabsahan.required' => 'Anda wajib menyatakan keabsahan data.',
            'pernyataan_keabsahan.accepted' => 'Anda wajib menyetujui pernyataan keabsahan data.',
        ];
    }
}