<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration AMAN — hanya menambah kolom baru.
 * Tidak mengubah, menghapus, atau me-reset kolom yang sudah ada.
 * Data existing tidak tersentuh sama sekali.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidates', function (Blueprint $table) {

            // Status seleksi tahap akhir (terpisah dari status verifikasi dokumen)
            // null  = belum diproses (default, tidak mengubah alur yang ada)
            // lolos = Lolos seleksi pacer
            // tidak_lolos = Tidak lolos seleksi pacer
            $table->enum('hasil_seleksi', ['lolos', 'tidak_lolos'])
                  ->nullable()
                  ->default(null)
                  ->after('catatan_admin')
                  ->comment('Hasil seleksi akhir pacer, hanya diisi setelah status = verified');

            // Catatan khusus untuk hasil seleksi (opsional, terpisah dari catatan_admin)
            $table->text('catatan_seleksi')
                  ->nullable()
                  ->default(null)
                  ->after('hasil_seleksi')
                  ->comment('Catatan admin terkait keputusan seleksi akhir');

            // Timestamp kapan keputusan seleksi ditetapkan
            $table->timestamp('seleksi_at')
                  ->nullable()
                  ->default(null)
                  ->after('catatan_seleksi')
                  ->comment('Waktu keputusan seleksi akhir ditetapkan');
        });
    }

    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn(['hasil_seleksi', 'catatan_seleksi', 'seleksi_at']);
        });
    }
};
