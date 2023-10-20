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
        Schema::connection('mysql_secondary')->table('asign_test', function (Blueprint $table) {
            $table->integer('total_point');
            $table->string('metode_training');
            $table->longText('notes_training');
            $table->string('start_class');
            $table->integer('module_read');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql_secondary')->table('asign_test', function (Blueprint $table) {
            //
        });
    }
};
