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
        Schema::create('journal_item', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('journal');
            $table->string('journal_entry');
            $table->string('account');
            $table->string('partner');
            $table->string('label');
            $table->decimal('debit');
            $table->decimal('credit');
            $table->string('product');
            $table->decimal('tax');
            $table->decimal('balance');
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
        Schema::dropIfExists('journal_item');
    }
};
