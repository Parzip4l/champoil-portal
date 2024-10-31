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
        Schema::create('emergency_details', function (Blueprint $table) {
            $table->id();
            $table->string('emergency_id');
            $table->string('name');
            $table->string('project');
            $table->string('distance');
            $table->string('category');
            $table->string('time_estimate')->null;
            $table->string('emergency_status');
            $table->string('request_status');
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
        Schema::dropIfExists('emergency_details');
    }
};
