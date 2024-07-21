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
        Schema::table('task_subtasks', function (Blueprint $table) {
            $table->string('latitude_start')->nullable();
            $table->string('longitude_start')->nullable();
            $table->time('time_start')->nullable();
            $table->string('latitude_end')->nullable();
            $table->string('longitude_end')->nullable();
            $table->time('time_end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_subtask', function (Blueprint $table) {
            //
        });
    }
};
