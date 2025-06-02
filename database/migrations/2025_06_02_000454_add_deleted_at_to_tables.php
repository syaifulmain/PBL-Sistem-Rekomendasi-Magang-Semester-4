<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('m_user', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('m_admin', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('m_dosen', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('m_mahasiswa', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('m_user', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('m_admin', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('m_dosen', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('m_mahasiswa', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
