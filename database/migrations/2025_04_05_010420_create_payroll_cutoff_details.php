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
        Schema::create('payroll_cutoff_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payroll_cutoff_setting_id');
            $table->unsignedBigInteger('company_id'); // redundant untuk efisiensi query
            $table->string('type'); // jabatan / organisasi
            $table->unsignedBigInteger('ref_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('process_date');
            $table->timestamps();

            $table->foreign('payroll_cutoff_setting_id')->references('id')->on('payroll_cutoff_setting')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payroll_cutoff_details');
    }
};
