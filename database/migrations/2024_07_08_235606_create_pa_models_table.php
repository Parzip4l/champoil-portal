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
        Schema::create('pa_table', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nik');
            $table->string('name');
            $table->string('periode');
            $table->text('detailsdata');
            $table->string('catatan_target');
            $table->string('nilai_keseluruhan');
            $table->text('komentar_masukan');
            $table->string('created_by');
            $table->string('approve_byemployee');
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
        Schema::dropIfExists('pa_table');
    }
};
