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
        Schema::create('pengajuan_cicilans', function (Blueprint $table) {
            $table->id();
            $table->integer('nik');
            $table->integer('project');
            $table->string('nomor_hp');
            $table->string('email');
            $table->longText('ktp');
            $table->string('barang_diajukan');
            $table->string('harga');
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
        Schema::dropIfExists('pengajuan_cicilans');
    }
};
