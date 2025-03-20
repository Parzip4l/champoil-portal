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
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('title'); 
            $table->string('icon')->nullable(); 
            $table->string('url')->nullable(); 
            $table->integer('parent_id')->nullable(); 
            $table->boolean('is_active')->default(true); 
            $table->integer('order')->default(0);
            $table->text('roles');
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
        Schema::dropIfExists('features');
    }
};
