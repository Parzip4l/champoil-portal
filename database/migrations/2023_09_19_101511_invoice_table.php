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
        Schema::create('invoice', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code');
            $table->string('type');
            $table->string('customer');
            $table->string('product_data');
            $table->date('invoice_date');
            $table->date('due_date');
            $table->string('total');
            $table->string('payment_status');
            $table->string('invoice_status');
            $table->string('sales_team');
            $table->string('created_by');
            $table->string('so_code');
            $table->integer('tax');
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
        Schema::dropIfExists('invoice');
    }
};
