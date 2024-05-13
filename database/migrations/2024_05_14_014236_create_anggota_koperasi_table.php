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
        Schema::create('anggota_koperasi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->string('employee_code');
            $table->string('company');
            $table->string('join_date');
            $table->string('member_status');
            $table->decimal('loan_limit',8,0);
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
        Schema::dropIfExists('anggota_koperasi');
    }
};
