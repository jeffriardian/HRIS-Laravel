<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInterrogationReportActorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interrogation_report_actors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('interrogation_report_id');
            $table->unsignedBigInteger('interrogation_report_actor_type_id');
            $table->unsignedBigInteger('employee_id')->default(0)->nullable();
            $table->unsignedBigInteger('external_employee_id')->default(0)->nullable();
            $table->text('description');
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
        Schema::dropIfExists('interrogation_report_actors');
    }
}
