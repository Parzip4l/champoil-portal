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
        Schema::connection('mysql_secondary')->create('project_details', function (Blueprint $table) {
            $table->id();
            $table->string('project_code');
            $table->string('jabatan');
            $table->BigInteger('kebutuhan');
            $table->BigInteger('p_gajipokok');
            $table->BigInteger('p_bpjstk');
            $table->BigInteger('p_bpjs_ks');
            $table->BigInteger('p_thr');
            $table->BigInteger('p_tkerja');
            $table->BigInteger('p_tseragam');
            $table->BigInteger('p_tlain');
            $table->BigInteger('p_training');
            $table->BigInteger('p_operasional');
            $table->BigInteger('p_membership');
            $table->BigInteger('r_deduction');
            $table->BigInteger('p_deduction');
            $table->BigInteger('tp_gapok');
            $table->BigInteger('tp_bpjstk');
            $table->BigInteger('tp_bpjsks');
            $table->BigInteger('tp_thr');
            $table->BigInteger('tp_tunjangankerja');
            $table->BigInteger('tp_tunjanganseragam');
            $table->BigInteger('tp_tunjanganlainnya');
            $table->BigInteger('tp_training');
            $table->BigInteger('tp_operasional');
            $table->BigInteger('tp_ppn');
            $table->BigInteger('tp_pph');
            $table->BigInteger('tp_cashin');
            $table->BigInteger('tp_total');
            $table->BigInteger('tp_membership');
            $table->BigInteger('tp_bulanan');
            $table->BigInteger('rate_harian');
            $table->string('created_by')->nullable();
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
        Schema::dropIfExists('project_details');
    }
};
