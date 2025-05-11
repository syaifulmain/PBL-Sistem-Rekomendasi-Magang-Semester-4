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
        Schema::table('m_periode_magang', function (Blueprint $table) {
            $table->date('tanggal_pendaftaran_mulai')->nullable();
            $table->date('tanggal_pendaftaran_selesai')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_periode_magang', function (Blueprint $table) {
            $table->dropColumn(['tanggal_pendaftaran_mulai', 'tanggal_pendaftaran_selesai']);
        });
    }
};
