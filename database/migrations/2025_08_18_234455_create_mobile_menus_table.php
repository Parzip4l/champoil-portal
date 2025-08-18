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
        Schema::create('mobile_menus', function (Blueprint $table) {
            $table->id();
            $table->string('menu_name');
            $table->string('icon')->nullable();
            $table->string('route_link');
            $table->integer('urutan');
            $table->boolean('maintenance')->default(0);
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
        Schema::dropIfExists('mobile_menus');
    }
};
