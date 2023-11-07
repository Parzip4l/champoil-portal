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
        Schema::create('payrolinfos', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code');
            $table->string('bpjs_kes')->nullable();
            $table->string('bpjs_tk');
            $table->string('npwp')->nullable();
            $table->string('bank_name');
            $table->string('bank_number');
            $table->string('ptkp');
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
        Schema::dropIfExists('payrolinfos');
    }
};
