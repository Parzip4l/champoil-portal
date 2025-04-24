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
        Schema::table('company_work_location_salary', function (Blueprint $table) {
            DB::statement('ALTER TABLE company_work_location_salary MODIFY position_id CHAR(36)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_work_location_salary', function (Blueprint $table) {
            DB::statement('ALTER TABLE company_work_location_salary MODIFY position_id INT');
        });
    }
};
