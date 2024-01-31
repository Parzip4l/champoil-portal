<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('karyawan', function (Blueprint $table) {
            $table->string('divisi')->nullable();
            $table->string('telepon_darurat')->nullable();
            $table->string('alamat_ktp')->nullable();
            $table->string('pendidikan_trakhir')->nullable();
            $table->string('jurusan')->nullable();
            $table->string('sertifikasi')->nullable();
            $table->string('expired_sertifikasi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('karyawan', function (Blueprint $table) {
            //
        });
    }
};
