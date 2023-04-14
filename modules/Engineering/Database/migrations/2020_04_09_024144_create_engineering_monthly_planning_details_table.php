<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEngineeringMonthlyPlanningDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('engineering_monthly_planning_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('engineering_monthly_planning_id');
            $table->unsignedInteger('machine_group_id');
            $table->integer('ps1')->nullable();
            $table->integer('ps2')->nullable();
            $table->integer('ps3')->nullable();
            $table->integer('ps4')->nullable();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
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
        Schema::dropIfExists('engineering_monthly_planning_details');
    }
}
