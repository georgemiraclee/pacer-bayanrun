<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BalkeCandidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'email',
        'no_wa',
        'tanggal_balke',
        'jam_balke',
        'balke_wa_sent',
        'balke_wa_sent_at',
        'balke_wa_failed',
    ];

    protected $casts = [
        'balke_wa_sent'    => 'boolean',
        'balke_wa_sent_at' => 'datetime',
        'balke_wa_failed'  => 'boolean',
    ];
}