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
        Schema::create('additional_component_details', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code');
            $table->string('employee_name');
            $table->string('code_master');
            $table->string('component_code');
            $table->string('component_name');
            $table->decimal('nominal', 10, 2);
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
        Schema::dropIfExists('additional_component_details');
    }
};
