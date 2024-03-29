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
        Schema::create('absen_backup', function (Blueprint $table) {
            $table->id();
            $table->string('nik');
            $table->date('tanggal');
            $table->string('project');
            $table->string('clock_in');
            $table->string('clock_out');
            $table->string('latitude');
            $table->string('longtitude');
            $table->string('latitude_out');
            $table->string('longtitude_out');
            $table->string('status');
            $table->text('photo');
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
        Schema::dropIfExists('absen_backups');
    }
};
