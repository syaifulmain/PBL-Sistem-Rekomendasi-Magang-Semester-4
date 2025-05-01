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
        Schema::create('m_lokasi_perusahaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained('m_perusahaan')->onDelete('cascade');
            $table->foreignId('negara_id')->constrained('m_negara')->onDelete('cascade');
            $table->foreignId('provinsi_id')->nullable()->constrained('m_provinsi')->onDelete('cascade');
            $table->foreignId('kabupaten_id')->nullable()->constrained('m_kabupaten')->onDelete('cascade');
            $table->foreignId('kecamatan_id')->nullable()->constrained('m_kecamatan')->onDelete('cascade');
            $table->foreignId('desa_id')->nullable()->constrained('m_desa')->onDelete('cascade');
            $table->string('alamat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_lokasi_perusahaan');
    }
};
