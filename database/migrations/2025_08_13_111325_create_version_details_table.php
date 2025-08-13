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
        Schema::create('version_details', function (Blueprint $table) {
            $table->id();
            $table->string('app_name', 100);
            $table->unsignedBigInteger('platform_id');
            $table->integer('version_code');
            $table->string('version_name', 20);
            $table->text('changelog')->nullable();
            $table->enum('release_type', ['beta', 'stable'])->default('stable');
            $table->text('download_url')->nullable();
            $table->dateTime('released_at')->default(DB::raw('CURRENT_TIMESTAMP'));
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
        Schema::dropIfExists('version_details');
    }
};
