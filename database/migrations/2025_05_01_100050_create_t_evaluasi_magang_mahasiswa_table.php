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
        Schema::create('t_evaluasi_magang_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('magang_id')->constrained('t_magang')->onDelete('cascade');
            $table->string('sertifikat_path')->nullable();
            $table->text('umpan_balik_mahasiswa')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_evaluasi_magang_mahasiswa');
    }
};
