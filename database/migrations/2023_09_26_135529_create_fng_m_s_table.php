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
        Schema::create('fng_m_s', function (Blueprint $table) {
            $table->id();
            $table->integer('supreme_15');
            $table->integer('supreme_4');
            $table->integer('optima_15');
            $table->integer('super');
            $table->integer('f300');
            $table->integer('heavy_loader');
            $table->integer('xtreme');
            $table->integer('power_15');
            $table->integer('power_10');
            $table->integer('power_4');
            $table->integer('wh300');
            $table->integer('active_10');
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
        Schema::dropIfExists('fng_m_s');
    }
};
