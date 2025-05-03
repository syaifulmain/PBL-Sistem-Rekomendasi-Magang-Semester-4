<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('t_dokumen_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('m_user')->onDelete('cascade');
            $table->foreignId('jenis_dokumen_id')->constrained('m_jenis_dokumen')->onDelete('cascade');
            $table->string('nama', 100);
            $table->string('path', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_dokumen_user');
    }
};
