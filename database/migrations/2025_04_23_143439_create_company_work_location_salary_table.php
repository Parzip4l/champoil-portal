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
        Schema::create('company_work_location_salary', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('work_location_id');
            $table->unsignedBigInteger('position_id');
            $table->decimal('monthly_salary', 15, 2)->nullable();
            $table->decimal('daily_rate', 15, 2)->nullable();
            $table->timestamps();
        
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
            $table->foreign('work_location_id')->references('id')->on('company_work_location')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_work_location_salary');
    }
};
