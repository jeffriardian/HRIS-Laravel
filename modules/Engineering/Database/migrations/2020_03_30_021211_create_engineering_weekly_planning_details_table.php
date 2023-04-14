<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEngineeringWeeklyPlanningDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('engineering_weekly_planning_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('engineering_weekly_planning_id');
            $table->unsignedInteger('machine_group_id');
            $table->string('1')->nullable();
            $table->string('2')->nullable();
            $table->string('3')->nullable();
            $table->string('4')->nullable();
            $table->string('5')->nullable();
            $table->string('6')->nullable();
            $table->string('7')->nullable();
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
        Schema::dropIfExists('engineering_weekly_planning_details');
    }
}
