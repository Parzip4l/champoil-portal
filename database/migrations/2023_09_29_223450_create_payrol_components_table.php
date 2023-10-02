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
        Schema::create('payrol_components', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code');
            $table->bigInteger('basic_salary');
            $table->text('allowances');
            $table->text('deductions');
            $table->bigInteger('net_salary');
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
        Schema::dropIfExists('payrol_components');
    }
};
