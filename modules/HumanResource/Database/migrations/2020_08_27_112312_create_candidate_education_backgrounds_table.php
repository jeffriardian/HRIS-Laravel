<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCandidateEducationBackgroundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_education_backgrounds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('candidate_id');
            $table->string('school_name');
            $table->string('major');
            $table->string('city');
            $table->year('entry_year');
            $table->year('graduation_year');
            $table->string('score', 30);
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
        Schema::dropIfExists('candidate_education_backgrounds');
    }
}
