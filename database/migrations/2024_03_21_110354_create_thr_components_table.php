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
        Schema::create('thr_component', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('employee_code');
            $table->string('gaji_pokok');
            $table->text('allowances');
            $table->text('deductions');
            $table->string('thp');
            $table->string('company');
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
        Schema::dropIfExists('thr_component');
    }
};
