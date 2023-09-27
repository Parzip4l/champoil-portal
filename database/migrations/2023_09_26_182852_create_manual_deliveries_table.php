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
        Schema::create('manual_deliveries', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_order');
            $table->date('target_kirim');
            $table->date('tanggal_kirim');
            $table->string('customer');
            $table->string('nomor_so');
            $table->string('ekspedisi');
            $table->string('nama_barang');
            $table->string('total_order');
            $table->string('sisa_order');
            $table->string('driver');
            $table->string('status');
            $table->string('keterangan');
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
        Schema::dropIfExists('manual_deliveries');
    }
};
