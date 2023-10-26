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
        Schema::connection('mysql_secondary')->create('master_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('jam_mulai_shift_1');
            $table->string('jam_akhir_shift_1');
            $table->string('jam_mulai_shift_2');
            $table->string('jam_akhir_shift_2');
            $table->string('jam_mulai_shift_3');
            $table->string('jam_akhir_shift_3');
            $table->string('jam_mulai_shift_4');
            $table->string('jam_akhir_shift_4');
            $table->string('project_id');
            $table->string('status');
            $table->string('unix_code');

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
        Schema::dropIfExists('master_tasks');
    }
};
