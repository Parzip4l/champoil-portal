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
        Schema::create('payment_regist', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('journal');
            $table->string('payment_method');
            $table->string('recipient_bank_account');
            $table->string('amount');
            $table->date('payment_date');
            $table->string('memo');
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
        Schema::dropIfExists('payment_regist');
    }
};
