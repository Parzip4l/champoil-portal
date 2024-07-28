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
        Schema::create('nt_customer', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('alamat');
            $table->string('company');
            $table->string('handphone');
            $table->string('job_status');
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
        Schema::dropIfExists('nt_cust');
    }
};
