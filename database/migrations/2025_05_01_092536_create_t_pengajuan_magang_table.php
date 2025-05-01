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
        Schema::create('t_pengajuan_magang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('m_mahasiswa')->onDelete('cascade');
            $table->foreignId('lowongan_magang_id')->constrained('t_lowongan_magang')->onDelete('cascade');
            $table->date('tanggal_pengajuan');
            $table->enum('status', ['diajukan', 'disetujui', 'ditolak', 'batal'])->default('diajukan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_pengajuan_magang');
    }
};
