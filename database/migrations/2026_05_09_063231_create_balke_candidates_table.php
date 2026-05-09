<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('balke_candidates', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->nullable();
            $table->string('no_wa', 25)->nullable();
            $table->string('tanggal_balke')->nullable();   // e.g. "17 Mei 2026"
            $table->string('jam_balke', 10)->nullable();   // e.g. "05:30"

            /* WA blast tracking */
            $table->boolean('balke_wa_sent')->default(false);
            $table->timestamp('balke_wa_sent_at')->nullable();
            $table->boolean('balke_wa_failed')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('balke_candidates');
    }
};