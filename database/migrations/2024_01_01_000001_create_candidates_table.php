<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('nama');
            $table->date('tanggal_lahir');
            $table->string('domisili');
            $table->text('alamat');
            $table->string('ktp_file'); // path file KTP
            $table->string('instagram');
            $table->string('strava');

            // Full Marathon
            $table->boolean('is_full_marathon')->default(false);
            $table->string('fm_event')->nullable();
            $table->year('fm_year')->nullable();
            $table->string('fm_certificate')->nullable();

            // Half Marathon
            $table->boolean('is_half_marathon')->default(false);
            $table->string('hm_event')->nullable();
            $table->year('hm_year')->nullable();
            $table->string('hm_certificate')->nullable();

            // Untuk yang belum pernah FM/HM - catatan tambahan
            $table->text('pengalaman_lari')->nullable();

            // Status seleksi
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('catatan_admin')->nullable(); // catatan dari admin saat reject/verify

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
