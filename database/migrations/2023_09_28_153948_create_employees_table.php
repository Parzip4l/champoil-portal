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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('code');
            $table->bigInteger('nik')->length(17)->unsigned();
            $table->string('nama');
            $table->text('alamat');
            $table->string('jabatan');
            $table->string('organisasi');
            $table->string('status_kontrak');
            $table->string('join_date');
            $table->string('end_contract');
            $table->string('email');
            $table->string('phone');
            $table->string('pernikahan');
            $table->string('agama');
            $table->date('tanggal_lahir');
            $table->string('tempat_lahir');
            $table->string('jenis_kelamin');
            $table->string('gambar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
