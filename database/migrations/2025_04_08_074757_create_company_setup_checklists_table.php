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
        Schema::create('company_setup_checklists', function (Blueprint $table) {
            $table->id();
            $table->string('company_code'); // atau company_id kalau kamu pakai relasi ID
            $table->string('key'); // misal: 'company_profile', 'first_employee', dst
            $table->boolean('is_completed')->default(false);
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
        Schema::dropIfExists('company_setup_checklists');
    }
};
