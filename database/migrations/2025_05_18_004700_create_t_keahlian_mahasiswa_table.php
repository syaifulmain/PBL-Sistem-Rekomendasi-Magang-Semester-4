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
        Schema::create('t_keahlian_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('m_mahasiswa')->onDelete('cascade');
            $table->foreignId('keahlian_teknis_id')->constrained('m_keahlian_teknis')->onDelete('cascade');
            $table->enum('level', [1,2,3])->default(1)->comment('1 = Dasar, 2 = Menengah, 3 = Mahir');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_keahlian_mahasiswa');
    }
};
