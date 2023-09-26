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
        Schema::create('penetrasis', function (Blueprint $table) {
            $table->id();
            $table->string('batch');
            $table->string('product');
            $table->integer('p_process');
            $table->string('k_process');
            $table->string('k_fng');
            $table->integer('p_fng');
            $table->string('checker');
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
        Schema::dropIfExists('penetrasis');
    }
};
