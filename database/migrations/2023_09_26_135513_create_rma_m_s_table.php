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
        Schema::create('rma_m_s', function (Blueprint $table) {
            $table->id();
            $table->integer('oli_bahan');
            $table->integer('oli_service');
            $table->integer('oli_trafo');
            $table->integer('lemak');
            $table->integer('wandes');
            $table->integer('pfad');
            $table->integer('kapur');
            $table->integer('latex');
            $table->integer('minarex');
            $table->integer('s_merah');
            $table->integer('s_hijau');
            $table->integer('s_kuning');
            $table->integer('s_biru');
            $table->integer('s_kuhl');
            $table->integer('tackifier_22');
            $table->integer('tackifier_champ');
            $table->integer('natrium_bicarbonat');
            $table->integer('soda_ash');
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
        Schema::dropIfExists('rma_m_s');
    }
};
