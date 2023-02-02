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
        Schema::create('interview_candidate_schedule', function (Blueprint $table) {
            $table->id();
            $table->BigInteger('interview_candidate_id');
            $table->date('interview_date');
            $table->text('interview_time');
            $table->BigInteger('interviewer_id');
            $table->enum('interview_type', ['Technical Round1', 'Technical Round2', 'HR R']);
            $table->Integer('communication');
            $table->Integer('technical');
            $table->Integer('attitude');
            $table->text('feedback_points');
            $table->enum('passed', ['Yes', 'No']);
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
        Schema::dropIfExists('interview_candidate_schedule');
    }
};
