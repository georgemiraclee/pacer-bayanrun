<?php

namespace App\Models;

use App\Enums\CandidateStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        // Data Pribadi
        'email', 'nik','no_hp', 'nama', 'tanggal_lahir', 'domisili', 'alamat',
        'ktp_file', 'instagram', 'strava',
        // Full & Half Marathon
        'is_full_marathon', 'fm_event', 'fm_year', 'fm_certificate',
        'is_half_marathon', 'hm_event', 'hm_year', 'hm_certificate',
        // 10K & 5K
        'is_10k', 'race_10k_event', 'race_10k_year', 'race_10k_certificate',
        'is_5k', 'race_5k_event', 'race_5k_year', 'race_5k_certificate',
        // Trail
        'trail_status', 'trail_event', 'trail_year', 'trail_certificate',
        // Mileage
        'mileage_dec_2025', 'mileage_dec_graph',
        'mileage_jan_2026', 'mileage_jan_graph',
        'mileage_feb_2026', 'mileage_feb_graph',
        'mileage_mar_2026', 'mileage_mar_graph',
        // Best Time
        'best_time_fm', 'best_time_fm_file',
        'best_time_hm', 'best_time_hm_file',
        'best_time_10k','best_time_10k_file',
        'best_time_5k', 'best_time_5k_file',
        // Pacer Experience
        'is_pacer_experience', 'pacer_event_list', 'pacer_distance_pace',
        // Essay
        'essay_running_world', 'essay_pacer_definition',
        // Komitmen
        'alasan_pantas', 'preferred_distance', 'komitmen', 'izin_keluarga',
        // Dokumen Final
        'waiver_file', 'pernyataan_keabsahan',
        // Status verifikasi dokumen
        'status', 'catatan_admin',
        // ── BARU: Hasil seleksi pacer tahap akhir ──
        'hasil_seleksi', 'catatan_seleksi', 'seleksi_at',
    ];

    protected $casts = [
        'is_full_marathon'     => 'boolean',
        'is_half_marathon'     => 'boolean',
        'is_pacer_experience'  => 'boolean',
        'pernyataan_keabsahan' => 'boolean',
        'preferred_distance'   => 'array',
        'status'               => CandidateStatus::class,
        'seleksi_at'           => 'datetime',
    ];

    // ── Helper: tampilkan tanggal lahir ─────────────────────────────
    public function getTanggalLahirFormattedAttribute(): string
    {
        $raw = $this->tanggal_lahir ?? '';
        if (empty($raw)) return '—';

        if (preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $raw, $m)) {
            $bulan = ['','Januari','Februari','Maret','April','Mei','Juni',
                      'Juli','Agustus','September','Oktober','November','Desember'];
            return $m[1].' '.($bulan[(int)$m[2]] ?? $m[2]).' '.$m[3];
        }

        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $raw, $m)) {
            $bulan = ['','Januari','Februari','Maret','April','Mei','Juni',
                      'Juli','Agustus','September','Oktober','November','Desember'];
            return $m[3].' '.($bulan[(int)$m[2]] ?? $m[2]).' '.$m[1];
        }

        return $raw;
    }

    // ── Helper: hitung usia ────────────────────────────────────────
    public function getUsiaAttribute(): ?int
    {
        $raw = $this->tanggal_lahir ?? '';
        if (empty($raw)) return null;

        try {
            if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $raw, $m)) {
                $tgl = \Carbon\Carbon::createFromDate((int)$m[3], (int)$m[2], (int)$m[1]);
            } elseif (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $raw, $m)) {
                $tgl = \Carbon\Carbon::createFromDate((int)$m[1], (int)$m[2], (int)$m[3]);
            } else {
                return null;
            }
            return $tgl->age;
        } catch (\Exception $e) {
            return null;
        }
    }

    // ── Helper: total mileage ─────────────────────────────────────
    public function totalMileage(): float
    {
        return (float)($this->mileage_dec_2025 ?? 0)
             + (float)($this->mileage_jan_2026 ?? 0)
             + (float)($this->mileage_feb_2026 ?? 0)
             + (float)($this->mileage_mar_2026 ?? 0);
    }

    // ── Status verifikasi dokumen ─────────────────────────────────
    public function isPending(): bool  { return $this->status === CandidateStatus::Pending; }
    public function isVerified(): bool { return $this->status === CandidateStatus::Verified; }
    public function isRejected(): bool { return $this->status === CandidateStatus::Rejected; }

    // ── Hasil seleksi pacer ───────────────────────────────────────
    public function isLolos(): bool      { return $this->hasil_seleksi === 'lolos'; }
    public function isTidakLolos(): bool { return $this->hasil_seleksi === 'tidak_lolos'; }
    public function belumDiseleksi(): bool { return is_null($this->hasil_seleksi); }

    /**
     * Label hasil seleksi untuk UI
     */
    public function hasilSeleksiLabel(): string
    {
        return match($this->hasil_seleksi) {
            'lolos'       => 'Lolos Seleksi Pacer',
            'tidak_lolos' => 'Tidak Lolos Seleksi',
            default       => 'Belum Diseleksi',
        };
    }

    // ── Scopes ───────────────────────────────────────────────────
    public function scopeByStatus($q, $s)       { return $q->where('status', $s); }
    public function scopeByDomisili($q, $d)     { return $q->where('domisili', 'like', "%{$d}%"); }
    public function scopeLolos($q)              { return $q->where('hasil_seleksi', 'lolos'); }
    public function scopeTidakLolos($q)         { return $q->where('hasil_seleksi', 'tidak_lolos'); }
    public function scopeBelumDiseleksi($q)     { return $q->whereNull('hasil_seleksi'); }
}