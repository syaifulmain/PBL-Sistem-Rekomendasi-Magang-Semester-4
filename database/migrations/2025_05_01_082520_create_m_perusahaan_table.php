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
        Schema::create('m_perusahaan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('alamat')->nullable()->comment('Gabungan dari lokasi perusahaan');
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_perusahaan');
    }
};
