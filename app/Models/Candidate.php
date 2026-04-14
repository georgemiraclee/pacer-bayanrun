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
        'email', 'nik', 'nama', 'tanggal_lahir', 'domisili', 'alamat',
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
        // Status
        'status', 'catatan_admin',
    ];

    protected $casts = [
        // ── PENTING: tanggal_lahir disimpan sebagai STRING DD-MM-YYYY dari OCR ──
        // Jangan di-cast ke 'date' karena format DD-MM-YYYY tidak dikenali Carbon.
        // Gunakan method tanggalLahirFormatted() untuk display.
        'is_full_marathon'     => 'boolean',
        'is_half_marathon'     => 'boolean',
        'is_pacer_experience'  => 'boolean',
        'pernyataan_keabsahan' => 'boolean',
        'preferred_distance'   => 'array',
        'status'               => CandidateStatus::class,
    ];

    // ── Helper: tampilkan tanggal lahir ─────────────────────────────
    // Mendukung format: DD-MM-YYYY, YYYY-MM-DD, DD/MM/YYYY
    public function getTanggalLahirFormattedAttribute(): string
    {
        $raw = $this->tanggal_lahir ?? '';
        if (empty($raw)) return '—';

        // Sudah DD-MM-YYYY → tampilkan langsung
        if (preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $raw, $m)) {
            $bulan = ['','Januari','Februari','Maret','April','Mei','Juni',
                      'Juli','Agustus','September','Oktober','November','Desember'];
            return $m[1].' '.($bulan[(int)$m[2]] ?? $m[2]).' '.$m[3];
        }

        // Format YYYY-MM-DD (dari input date HTML)
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $raw, $m)) {
            $bulan = ['','Januari','Februari','Maret','April','Mei','Juni',
                      'Juli','Agustus','September','Oktober','November','Desember'];
            return $m[3].' '.($bulan[(int)$m[2]] ?? $m[2]).' '.$m[1];
        }

        return $raw; // fallback tampilkan apa adanya
    }

    // ── Helper: hitung usia dari tanggal lahir string ────────────────
    public function getUsiaAttribute(): ?int
    {
        $raw = $this->tanggal_lahir ?? '';
        if (empty($raw)) return null;

        try {
            // Parse DD-MM-YYYY
            if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $raw, $m)) {
                $tgl = \Carbon\Carbon::createFromDate((int)$m[3], (int)$m[2], (int)$m[1]);
            }
            // Parse YYYY-MM-DD
            elseif (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $raw, $m)) {
                $tgl = \Carbon\Carbon::createFromDate((int)$m[1], (int)$m[2], (int)$m[3]);
            } else {
                return null;
            }
            return $tgl->age;
        } catch (\Exception $e) {
            return null;
        }
    }

    // ── Helper: total mileage ────────────────────────────────────────
    public function totalMileage(): float
    {
        return (float)($this->mileage_dec_2025 ?? 0)
             + (float)($this->mileage_jan_2026 ?? 0)
             + (float)($this->mileage_feb_2026 ?? 0)
             + (float)($this->mileage_mar_2026 ?? 0);
    }

    // ── Status helpers ───────────────────────────────────────────────
    public function isPending(): bool  { return $this->status === CandidateStatus::Pending; }
    public function isVerified(): bool { return $this->status === CandidateStatus::Verified; }
    public function isRejected(): bool { return $this->status === CandidateStatus::Rejected; }

    // ── Scopes ──────────────────────────────────────────────────────
    public function scopeByStatus($q, $s)   { return $q->where('status', $s); }
    public function scopeByDomisili($q, $d) { return $q->where('domisili', 'like', "%{$d}%"); }
}