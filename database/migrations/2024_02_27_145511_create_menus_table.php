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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id');
            $table->string('title');
            $table->string('url');
            $table->longText('description');
            $table->integer('menu_order');
            $table->integer('mobile');
            $table->string('is_view');
            $table->string('is_create');
            $table->string('is_edit');
            $table->string('is_delete');
            $table->string('is_icon');
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
        Schema::dropIfExists('menus');
    }
};
