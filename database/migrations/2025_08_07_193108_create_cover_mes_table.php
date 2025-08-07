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
        Schema::create('cover_mes', function (Blueprint $table) {
            $table->id();
            $table->integer('id_perusahaan')->nullable();
            $table->biginteger('nik_cover')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('shift');
            $table->string('requirements');
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
        Schema::dropIfExists('cover_mes');
    }
};
