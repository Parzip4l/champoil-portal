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
        Schema::create('asset_transaksi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('transaksi_code');
            $table->string('name');
            $table->string('project');
            $table->string('asset_id');
            $table->string('vendor_id');
            $table->string('qty');
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
        Schema::dropIfExists('asset_transaksi');
    }
};
