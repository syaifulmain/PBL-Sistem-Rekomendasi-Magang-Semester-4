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
        Schema::table('t_pengajuan_magang', function (Blueprint $table) {
            $table->boolean('rekomendasi')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_pengajuan_magang', function (Blueprint $table) {
            $table->dropColumn('rekomendasi');
        });
    }
};
