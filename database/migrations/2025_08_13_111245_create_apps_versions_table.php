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
        Schema::create('apps_versions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->notNullable(); // Nama platform (Android, iOS, Web)
            $table->string('code', 20)->unique(); // Kode unik (android, ios, web)
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
        Schema::dropIfExists('apps_versions');
    }
};
