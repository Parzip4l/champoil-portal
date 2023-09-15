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
        Schema::create('sales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code');
            $table->string('customer');
            $table->string('data_product');
            $table->string('delivery_date');
            $table->string('delivery_status');
            $table->string('ekspedition');
            $table->string('invoice_status');
            $table->string('notes');
            $table->string('order_date');
            $table->string('payment_terms');
            $table->string('sales_team');
            $table->string('total');
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
        Schema::dropIfExists('sales');
    }
};
