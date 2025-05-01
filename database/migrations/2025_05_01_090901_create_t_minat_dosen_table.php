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
        Schema::create('t_minat_dosen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->constrained('m_dosen')->onDelete('cascade');
            $table->foreignId('bidang_keahlian_id')->constrained('m_bidang_keahlian')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_minat_dosen');
    }
};
