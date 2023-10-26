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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->decimal('amount', 10, 2); // Besaran nominal pinjaman
            $table->decimal('remaining_amount', 10, 2); // Sisa hutang
            $table->integer('installments'); // Jumlah cicilan (bulan)
            $table->decimal('installment_amount', 10, 2); // Besaran cicilan per bulan
            $table->boolean('is_paid')->default(false); // Status apakah pinjaman sudah lunas
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
        Schema::dropIfExists('loan_models');
    }
};
