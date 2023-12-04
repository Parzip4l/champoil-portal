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
        Schema::create('karyawan_resign', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_code');
            $table->integer('ktp');
            $table->string('nama');
            $table->date('join_date');
            $table->longText('meta_karyawan')->nullable()->default(null);
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
        Schema::dropIfExists('karyawan_resign');
    }
};
