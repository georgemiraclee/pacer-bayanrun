<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewConfirmation extends Model
{
    protected $fillable = [
        'interview_session_id',
        'status',
        'request_hari',
        'alasan',
        'ip_address',
    ];

    public function session()
    {
        return $this->belongsTo(InterviewSession::class, 'interview_session_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'hadir'     => 'Siap Hadir',
            'ganti_hari'=> 'Request Ganti Hari',
            default     => '—',
        };
    }
}
