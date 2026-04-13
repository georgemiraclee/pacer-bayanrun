<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();

            // ── SECTION 1: Data Pribadi ─────────────────────────────
            $table->string('email')->unique();
            $table->string('nama');
            $table->date('tanggal_lahir');
            $table->string('domisili');
            $table->text('alamat');
            $table->string('ktp_file');
            $table->string('instagram');
            $table->string('strava');

            // ── SECTION 2: Full Marathon ────────────────────────────
            $table->boolean('is_full_marathon')->default(false);
            $table->string('fm_event')->nullable();
            $table->unsignedSmallInteger('fm_year')->nullable();
            $table->string('fm_certificate')->nullable();

            // ── SECTION 3: Half Marathon ────────────────────────────
            $table->boolean('is_half_marathon')->default(false);
            $table->string('hm_event')->nullable();
            $table->unsignedSmallInteger('hm_year')->nullable();
            $table->string('hm_certificate')->nullable();

            // ── SECTION 4: 10K ──────────────────────────────────────
            $table->enum('is_10k', ['pernah', 'tidak', 'skip'])->default('tidak');
            $table->string('race_10k_event')->nullable();
            $table->unsignedSmallInteger('race_10k_year')->nullable();
            $table->string('race_10k_certificate')->nullable();

            // ── SECTION 5: 5K ───────────────────────────────────────
            $table->enum('is_5k', ['pernah', 'tidak', 'skip'])->default('tidak');
            $table->string('race_5k_event')->nullable();
            $table->unsignedSmallInteger('race_5k_year')->nullable();
            $table->string('race_5k_certificate')->nullable();

            // ── SECTION 6: Trail / Non Road Race ───────────────────
            // 'trail' = pernah trail, 'none' = tidak pernah sama sekali, 'skip' = lewati
            $table->string('trail_status')->nullable(); // trail | none | skip
            $table->string('trail_event')->nullable();
            $table->unsignedSmallInteger('trail_year')->nullable();
            $table->string('trail_certificate')->nullable();

            // ── SECTION 7: Mileage (wajib semua) ───────────────────
            $table->decimal('mileage_dec_2025', 8, 2)->nullable();
            $table->string('mileage_dec_graph')->nullable();
            $table->decimal('mileage_jan_2026', 8, 2)->nullable();
            $table->string('mileage_jan_graph')->nullable();
            $table->decimal('mileage_feb_2026', 8, 2)->nullable();
            $table->string('mileage_feb_graph')->nullable();
            $table->decimal('mileage_mar_2026', 8, 2)->nullable();
            $table->string('mileage_mar_graph')->nullable();

            // ── SECTION 8: Best Time / Catatan Waktu ───────────────
            $table->string('best_time_fm')->nullable();     // format: H:MM:SS
            $table->string('best_time_fm_file')->nullable();
            $table->string('best_time_hm')->nullable();
            $table->string('best_time_hm_file')->nullable();
            $table->string('best_time_10k')->nullable();
            $table->string('best_time_10k_file')->nullable();
            $table->string('best_time_5k')->nullable();
            $table->string('best_time_5k_file')->nullable();

            // ── SECTION 9: Pengalaman Pacer ─────────────────────────
            $table->boolean('is_pacer_experience')->default(false);
            $table->text('pacer_event_list')->nullable();   // nama event + tahun
            $table->text('pacer_distance_pace')->nullable();// jarak & pace

            // ── SECTION 10: Essay / Pemahaman ───────────────────────
            $table->text('essay_running_world')->nullable();  // pandangan dunia lari
            $table->text('essay_pacer_definition')->nullable();// pengertian pacer

            // ── SECTION 11: Komitmen & Preferensi ──────────────────
            $table->text('alasan_pantas')->nullable();          // mengapa pantas
            // preferred_distance: JSON array ['hm','10k','5k','any']
            $table->json('preferred_distance')->nullable();
            // komitmen: ya_siap | tidak_siap | mencoba_menyesuaikan
            $table->string('komitmen')->nullable();
            // izin_keluarga: ya | belum
            $table->string('izin_keluarga')->nullable();

            // ── SECTION 12: Dokumen Final ───────────────────────────
            $table->string('waiver_file')->nullable();          // surat persetujuan bermaterai
            $table->boolean('pernyataan_keabsahan')->default(false);

            // ── Status Seleksi ──────────────────────────────────────
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('catatan_admin')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};