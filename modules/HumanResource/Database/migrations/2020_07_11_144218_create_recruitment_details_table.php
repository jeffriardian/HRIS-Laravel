<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecruitmentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruitment_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('recruitment_id');
            $table->unsignedInteger('candidate_id');
            $table->unsignedInteger('recruitment_step_id');
            $table->unsignedInteger('recruitment_step_parameter_id');
            $table->unsignedInteger('score_id');
            $table->text('note');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recruitment_details');
    }
}
