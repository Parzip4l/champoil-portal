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
        Schema::create('gardapratama', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->integer('amount'); // Besaran nominal pinjaman
            $table->integer('remaining_amount'); // Sisa hutang
            $table->integer('installments'); // Jumlah cicilan (bulan)
            $table->integer('installment_amount'); // Besaran cicilan per bulan
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
        Schema::dropIfExists('gps');
    }
};
