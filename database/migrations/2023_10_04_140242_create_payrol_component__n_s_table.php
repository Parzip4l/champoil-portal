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
        Schema::create('payrol_component_ns', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code');
            $table->bigInteger('daily_salary');
            $table->text('allowances');
            $table->text('deductions');
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
        Schema::dropIfExists('payrol_component__n_s');
    }
};
