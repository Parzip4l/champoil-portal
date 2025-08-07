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
        Schema::create('cover_claims', function (Blueprint $table) {
            $table->id();
            $table->integer('cover_id');
            $table->bigInteger('nik');
            $table->integer('status')->default(0); // Status of the claim, e
            $table->bigInteger('action_by')->nullable(); // User who claimed the cover
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
        Schema::dropIfExists('cover_claims');
    }
};
