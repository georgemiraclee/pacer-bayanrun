<?php

namespace App\Enums;

enum CandidateStatus: string
{
    case Pending  = 'pending';
    case Verified = 'verified';
    case Rejected = 'rejected';

    /**
     * Label untuk tampilan di UI
     */
    public function label(): string
    {
        return match($this) {
            self::Pending  => 'Menunggu Review',
            self::Verified => 'Terverifikasi',
            self::Rejected => 'Ditolak',
        };
    }

    /**
     * Warna badge Tailwind
     */
    public function color(): string
    {
        return match($this) {
            self::Pending  => 'bg-yellow-100 text-yellow-800',
            self::Verified => 'bg-green-100 text-green-800',
            self::Rejected => 'bg-red-100 text-red-800',
        };
    }
}
