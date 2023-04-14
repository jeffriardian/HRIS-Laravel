<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEngineeringDailyPlanningDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('engineering_daily_planning_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('engineering_daily_planning_id');
            $table->unsignedInteger('machine_id');
            $table->unsignedInteger('service_type_id');
            $table->time('stop_at', 0)->nullable();
            $table->time('start_at', 0)->nullable();
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
        Schema::dropIfExists('engineering_daily_planning_details');
    }
}
