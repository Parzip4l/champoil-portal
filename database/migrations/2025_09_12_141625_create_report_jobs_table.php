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
        Schema::create('report_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // pdf / excel
            $table->string('project_name')->nullable();
            $table->text('params')->nullable(); // json request
            $table->text('file_paths')->nullable(); // json array file
            $table->enum('status', ['pending','processing','done','failed'])->default('pending');
            $table->text('error_message')->nullable();
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
        Schema::dropIfExists('report_jobs');
    }
};
