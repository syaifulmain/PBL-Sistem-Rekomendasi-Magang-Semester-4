<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('t_lowongan_magang', function (Blueprint $table) {
            $table->decimal('minimal_ipk', 3, 2)->nullable()->after('kuota');
            $table->string('insentif')->nullable()->after('minimal_ipk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('t_lowongan_magang', function (Blueprint $table) {
            $table->dropColumn(['minimal_ipk', 'insentif']);
        });
    }
};
