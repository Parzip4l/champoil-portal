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
        Schema::table('pajak', function (Blueprint $table) {
            $table->decimal('min_bruto', 10, 0);
            $table->decimal('max_bruto', 10, 0);
            $table->integer('persentase');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pajak', function (Blueprint $table) {
            //
        });
    }
};
