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
        Schema::create('t_lowongan_magang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained('m_perusahaan')->onDelete('cascade');
            $table->foreignId('periode_magang_id')->constrained('m_periode_magang')->onDelete('cascade');
            $table->string('judul', 100);
            $table->text('deskripsi');
            $table->text('persyaratan')->nullable();
            $table->integer('kuota')->default(1);
            $table->date('tanggal_mulai_daftar');
            $table->date('tanggal_selesai_daftar');
            $table->date('tanggal_mulai_magang');
            $table->date('tanggal_selesai_magang');
            $table->enum('status', ['buka', 'tutup', 'dibatalkan'])->default('buka');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_lowongan_magang');
    }
};
