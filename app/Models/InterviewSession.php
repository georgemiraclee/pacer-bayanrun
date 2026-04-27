<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class InterviewSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'token', 'nama', 'email', 'no_wa',
        'jadwal', 'waktu', 'durasi',
        'wa_sent', 'wa_sent_at',
    ];

    protected $casts = [
        'wa_sent'    => 'boolean',
        'wa_sent_at' => 'datetime',
    ];

    // ── Relasi ────────────────────────────────────────────────────
    public function confirmation()
    {
        return $this->hasOne(InterviewConfirmation::class);
    }

    // ── Helper: generate token unik ───────────────────────────────
    public static function generateToken(): string
    {
        do {
            $token = Str::random(16);
        } while (self::where('token', $token)->exists());

        return $token;
    }

    // ── Helper: format nomor WA untuk link wa.me ─────────────────
    public function getWaLinkAttribute(): string
    {
        $no = preg_replace('/\D/', '', $this->no_wa ?? '');
        if (str_starts_with($no, '0')) {
            $no = '62' . substr($no, 1);
        }
        if (!str_starts_with($no, '62')) {
            $no = '62' . $no;
        }
        return $no;
    }

    // ── Helper: status konfirmasi kandidat ───────────────────────
    public function getStatusKonfirmasiAttribute(): string
    {
        if (!$this->confirmation) return 'belum';
        return $this->confirmation->status;
    }

    // ── Helper: pesan WA blast ────────────────────────────────────
    public function buildWaMessage(): string
    {
        $link = route('interview.confirm', $this->token);

        return "Hai Kak {$this->nama} 👋\n\n"
            . "Kami dari *Tim Rekrutmen Calon Pacer Bayan Run 2026* mengundang anda untuk melanjutkan seleksi ke tahap selanjutnya, yaitu *Test Interview*, yang akan dilaksanakan pada:\n\n"
            . "📅 *Hari :* {$this->jadwal}\n"
            . "🕐 *Jam :* {$this->waktu} WITA\n"
            . "📍 *Tempat :* Kantor Bayan Balikpapan\n"
            . "Jl. M.T. Haryono Komplek Balikpapan Baru Blok D4 No.8-10 (Sebrang Boyolali BB)\n\n"
            . "Silahkan klik link di bawah ini untuk mengonfirmasi kedatangan:\n"
            . $link . "\n\n"
            . "_Tim Bayan Run 2026_";
    }

    // ── Scopes ────────────────────────────────────────────────────
    public function scopeByJadwal($q, $jadwal)
    {
        return $q->where('jadwal', $jadwal);
    }

    public function scopeSudahKonfirmasi($q)
    {
        return $q->whereHas('confirmation');
    }

    public function scopeBelumKonfirmasi($q)
    {
        return $q->whereDoesntHave('confirmation');
    }
}
