<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEngineeringAnnualPlanningDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('engineering_annual_planning_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('engineering_annual_planning_id');
            $table->unsignedInteger('machine_id');
            $table->string('1_1')->nullable();
            $table->string('1_2')->nullable();
            $table->string('1_3')->nullable();
            $table->string('1_4')->nullable();
            $table->string('2_1')->nullable();
            $table->string('2_2')->nullable();
            $table->string('2_3')->nullable();
            $table->string('2_4')->nullable();
            $table->string('3_1')->nullable();
            $table->string('3_2')->nullable();
            $table->string('3_3')->nullable();
            $table->string('3_4')->nullable();
            $table->string('4_1')->nullable();
            $table->string('4_2')->nullable();
            $table->string('4_3')->nullable();
            $table->string('4_4')->nullable();
            $table->string('5_1')->nullable();
            $table->string('5_2')->nullable();
            $table->string('5_3')->nullable();
            $table->string('5_4')->nullable();
            $table->string('6_1')->nullable();
            $table->string('6_2')->nullable();
            $table->string('6_3')->nullable();
            $table->string('6_4')->nullable();
            $table->string('7_1')->nullable();
            $table->string('7_2')->nullable();
            $table->string('7_3')->nullable();
            $table->string('7_4')->nullable();
            $table->string('8_1')->nullable();
            $table->string('8_2')->nullable();
            $table->string('8_3')->nullable();
            $table->string('8_4')->nullable();
            $table->string('9_1')->nullable();
            $table->string('9_2')->nullable();
            $table->string('9_3')->nullable();
            $table->string('9_4')->nullable();
            $table->string('10_1')->nullable();
            $table->string('10_2')->nullable();
            $table->string('10_3')->nullable();
            $table->string('10_4')->nullable();
            $table->string('11_1')->nullable();
            $table->string('11_2')->nullable();
            $table->string('11_3')->nullable();
            $table->string('11_4')->nullable();
            $table->string('12_1')->nullable();
            $table->string('12_2')->nullable();
            $table->string('12_3')->nullable();
            $table->string('12_4')->nullable();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->timestamps();
            $table->softDeletes();
        });

        // Schema::create('engineering_annual_planning_details', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('engineering_annual_planning_id');
        //     $table->unsignedInteger('machine_id');
        //     $table->unsignedInteger('type_service_id')->nullable();
        //     $table->integer('month');
        //     $table->integer('week');
        //     $table->boolean('is_active')->default(true);
        //     $table->unsignedInteger('created_by');
        //     $table->unsignedInteger('updated_by');
        //     $table->timestamps();
        //     $table->softDeletes();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('engineering_annual_planning_details');
    }
}
