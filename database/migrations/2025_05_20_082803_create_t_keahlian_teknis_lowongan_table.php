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
        Schema::create('t_keahlian_teknis_lowongan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lowongan_magang_id')->constrained('t_lowongan_magang')->onDelete('cascade');
            $table->foreignId('keahlian_teknis_id')->constrained('m_keahlian_teknis')->onDelete('restrict');
            $table->enum('level', ['pemula', 'senior', 'ahli']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_keahlian_teknis_lowongan');
    }
};
