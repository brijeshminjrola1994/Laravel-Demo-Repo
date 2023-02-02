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
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('free')->after('locale');
            $table->text('experience')->after('free');
            $table->Integer('reporting_manager')->after('experience');
            $table->Integer('primary_technology')->after('reporting_manager');
            $table->text('secondary_technology')->after('primary_technology');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('_users', function (Blueprint $table) {
            //
        });
    }
};
