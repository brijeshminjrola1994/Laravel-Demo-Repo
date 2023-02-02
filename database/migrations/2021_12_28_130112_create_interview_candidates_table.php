<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterviewCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interview_candidates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->unsignedBigInteger('designation_id');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('mobile_no');
            $table->string('gender');
            $table->text('address')->nullable();
            $table->text('resume')->nullable();
            $table->unsignedTinyInteger('notice_period')->default(0);
            $table->float('current_ctc')->default(0);
            $table->float('expected_ctc')->default(0);
            $table->float('offered_amount')->default(0);
            $table->unsignedTinyInteger('status')->default(1);
            $table->text('notes')->nullable(0);
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
        Schema::dropIfExists('interview_candidates');
    }
}
