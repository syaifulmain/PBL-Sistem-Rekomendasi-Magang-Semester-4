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
        Schema::create('m_user', function (Blueprint $table) {
            $table->id();
            $table->string('username', 100)->unique();
            $table->string('password', 255);
            $table->string('email')->nullable();
            $table->string('path_foto_profil')->nullable();
            $table->enum('level', ['ADMIN', 'DOSEN', 'MAHASISWA']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_user');
    }
};
