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
        Schema::create('emergency_request', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('user');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('category');
            $table->text('deskripsi');
            $table->string('status');
            $table->string('company');
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
        Schema::dropIfExists('emergency_request');
    }
};
