<?php

namespace App\Models;

use App\Enums\CandidateStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'nama',
        'tanggal_lahir',
        'domisili',
        'alamat',
        'ktp_file',
        'instagram',
        'strava',
        'is_full_marathon',
        'is_half_marathon',
        'fm_event',
        'fm_year',
        'fm_certificate',
        'hm_event',
        'hm_year',
        'hm_certificate',
        'pengalaman_lari',
        'status',
        'catatan_admin',
    ];

    protected $casts = [
        'tanggal_lahir'    => 'date',
        'is_full_marathon'  => 'boolean',
        'is_half_marathon'  => 'boolean',
        'status'           => CandidateStatus::class,
    ];

    // ───────────────────────────────────────────────
    // Scopes untuk filter di admin dashboard
    // ───────────────────────────────────────────────

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByDomisili($query, string $domisili)
    {
        return $query->where('domisili', 'like', "%{$domisili}%");
    }

    public function scopeHasFullMarathon($query)
    {
        return $query->where('is_full_marathon', true);
    }

    public function scopeHasHalfMarathon($query)
    {
        return $query->where('is_half_marathon', true);
    }

    // ───────────────────────────────────────────────
    // Helpers
    // ───────────────────────────────────────────────

    public function isPending(): bool
    {
        return $this->status === CandidateStatus::Pending;
    }

    public function isVerified(): bool
    {
        return $this->status === CandidateStatus::Verified;
    }

    public function isRejected(): bool
    {
        return $this->status === CandidateStatus::Rejected;
    }

    public function hasRaceExperience(): bool
    {
        return $this->is_full_marathon || $this->is_half_marathon;
    }
}
