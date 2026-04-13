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
        'email', 'nama', 'tanggal_lahir', 'domisili', 'alamat',
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
        'best_time_fm',  'best_time_fm_file',
        'best_time_hm',  'best_time_hm_file',
        'best_time_10k', 'best_time_10k_file',
        'best_time_5k',  'best_time_5k_file',
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
        'tanggal_lahir'        => 'date',
        'is_full_marathon'     => 'boolean',
        'is_half_marathon'     => 'boolean',
        'is_pacer_experience'  => 'boolean',
        'pernyataan_keabsahan' => 'boolean',
        'preferred_distance'   => 'array',
        'status'               => CandidateStatus::class,
    ];

    // ── Helpers ─────────────────────────────────────────────────

    public function isPending(): bool   { return $this->status === CandidateStatus::Pending; }
    public function isVerified(): bool  { return $this->status === CandidateStatus::Verified; }
    public function isRejected(): bool  { return $this->status === CandidateStatus::Rejected; }

    public function hasRoadRace(): bool
    {
        return $this->is_full_marathon || $this->is_half_marathon
            || $this->is_10k === 'pernah' || $this->is_5k === 'pernah';
    }

    public function totalMileage(): float
    {
        return (float)($this->mileage_dec_2025 ?? 0)
             + (float)($this->mileage_jan_2026 ?? 0)
             + (float)($this->mileage_feb_2026 ?? 0)
             + (float)($this->mileage_mar_2026 ?? 0);
    }

    // ── Scopes ──────────────────────────────────────────────────

    public function scopeByStatus($q, $s)    { return $q->where('status', $s); }
    public function scopeByDomisili($q, $d)  { return $q->where('domisili', 'like', "%{$d}%"); }
}