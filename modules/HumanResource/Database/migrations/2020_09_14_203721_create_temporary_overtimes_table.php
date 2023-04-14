<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemporaryOvertimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temporary_overtimes', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('approved_by');
            $table->date('request_date');
            $table->string('overtime_before_duration')->nullable();
            $table->string('overtime_after_duration')->nullable();
            $table->float('total_time');
            $table->string('attachment')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('shift_id');
            $table->time('schedule_in');
            $table->time('schedule_out');
            $table->boolean('without_reducing_rest_hours')->default(0)->nullable();
            $table->boolean('is_holiday')->default(0)->nullable();
            $table->boolean('is_saturday')->default(0)->nullable();
            $table->dateTime('start_time_holiday')->nullable();
            $table->dateTime('end_time_holiday')->nullable();
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
        Schema::dropIfExists('temporary_overtimes');
    }
}
