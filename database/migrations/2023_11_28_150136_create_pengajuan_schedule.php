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
        Schema::create('pengajuan_schedule', function (Blueprint $table) {
            $table->id();
            $table->string('schedule_code');
            $table->string('project');
            $table->string('employee');
            $table->string('tanggal');
            $table->string('shift');
            $table->string('namapengaju');
            $table->string('status');
            $table->string('disetujui_oleh')->nullable();
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
        Schema::dropIfExists('pengajuan_schedule');
    }
};
