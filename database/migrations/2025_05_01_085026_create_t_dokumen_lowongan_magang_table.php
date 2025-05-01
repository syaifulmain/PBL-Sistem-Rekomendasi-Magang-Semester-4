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
        Schema::create('t_dokumen_lowongan_magang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lowongan_magang_id')->constrained('t_lowongan_magang')->onDelete('cascade');
            $table->foreignId('jenis_dokumen_id')->constrained('m_jenis_dokumen')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_dokumen_lowongan_magang');
    }
};
