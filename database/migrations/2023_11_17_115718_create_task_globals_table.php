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
        Schema::connection('mysql_secondary')->create('task_globals', function (Blueprint $table) {
            $table->id();
            $table->string('unit_bisnis');
            $table->integer('project')->nullable();
            $table->string('task_name');
            $table->string('upload_file');
            $table->string('repeat_task');
            $table->longText('assign')->nullable();
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
        Schema::dropIfExists('task_globals');
    }
};
