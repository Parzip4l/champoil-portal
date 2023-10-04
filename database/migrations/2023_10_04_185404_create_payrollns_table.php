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
        Schema::create('payrollns', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code');
            $table->string('month');
            $table->string('year');
            $table->string('periode');
            $table->bigInteger('daily_salary');
            $table->bigInteger('total_absen');
            $table->bigInteger('lembur_salary');
            $table->bigInteger('jam_lembur')->nullable();
            $table->bigInteger('total_lembur');
            $table->bigInteger('uang_makan')->nullable();
            $table->bigInteger('uang_kerajinan')->nullable();
            $table->bigInteger('potongan_hutang');
            $table->bigInteger('potongan_mess')->nullable();
            $table->bigInteger('potongan_lain')->nullable();
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
        Schema::dropIfExists('payrollns');
    }
};
