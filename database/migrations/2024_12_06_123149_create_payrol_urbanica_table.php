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
        Schema::create('payrol_urbanica', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code');
            $table->string('periode');
            $table->string('year');
            $table->string('basic_salary');
            $table->text('allowances');
            $table->text('deductions');
            $table->string('thp');
            $table->string('payrol_status');
            $table->string('payslip_status');
            $table->string('run_by');
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
        Schema::dropIfExists('payrol_urbanica');
    }
};
