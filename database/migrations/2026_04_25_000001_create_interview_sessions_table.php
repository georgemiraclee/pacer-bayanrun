<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Tabel jadwal interview (diisi via import Excel) ─────────
        Schema::create('interview_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('token', 32)->unique()->index(); // link unik per kandidat
            $table->string('nama');
            $table->string('email')->nullable();
            $table->string('no_wa', 20)->nullable();
            $table->string('jadwal');          // "Senin, 27 April 2026"
            $table->string('waktu', 10);       // "18:45"
            $table->string('durasi')->default('15 Menit');
            $table->boolean('wa_sent')->default(false); // sudah di-blast atau belum
            $table->timestamp('wa_sent_at')->nullable();
            $table->timestamps();
        });

        // ── Tabel konfirmasi dari kandidat ──────────────────────────
        Schema::create('interview_confirmations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interview_session_id')
                  ->constrained('interview_sessions')
                  ->cascadeOnDelete();
            // hadir | ganti_hari
            $table->enum('status', ['hadir', 'ganti_hari']);
            $table->string('request_hari')->nullable(); // hari pilihan kalau ganti
            $table->text('alasan')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interview_confirmations');
        Schema::dropIfExists('interview_sessions');
    }
};
