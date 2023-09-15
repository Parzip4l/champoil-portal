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
        Schema::create('vendor_bills', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code');
            $table->string('bill_date');
            $table->string('accounting_date');
            $table->string('bank_receipt');
            $table->string('due_date');
            $table->string('journal');
            $table->string('payment_status');
            $table->string('purchase_details');
            $table->string('purchase_id');
            $table->string('status');
            $table->string('vendor');
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
        Schema::dropIfExists('vendor_bills');
    }
};
