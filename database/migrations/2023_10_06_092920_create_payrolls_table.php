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
        Schema::connection('mysql_secondary')->create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code');
            $table->string('periode');
            $table->BigInteger('basic_salary');
            $table->integer('aktif');
            $table->text('allowences');
            $table->text('deductions');
            $table->BigInteger('thp');
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
        Schema::dropIfExists('payrolls');
    }
};
