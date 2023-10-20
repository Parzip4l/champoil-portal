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
        Schema::connection('mysql_secondary')->create('asign_tests', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_code');
            $table->integer('id_test');
            $table->integer('status');
            $table->integer('total_point');
            $table->string('metode_training');
            $table->longText('notes_training');
            $table->string('start_class');
            $table->integer('module_read');
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
        Schema::dropIfExists('asign_tests');
    }
};
