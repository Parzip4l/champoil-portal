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
        Schema::create('thr_master', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('employee_code');
            $table->string('gaji_pokok');
            $table->text('allowances');
            $table->text('deductions');
            $table->string('thp');
            $table->string('thr_status');
            $table->string('slip_status');
            $table->string('company');
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
        Schema::dropIfExists('thr_master');
    }
};
