<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOvertimeShiftEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtime_shift_employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('overtime_id');
            $table->unsignedBigInteger('shift_id');
            $table->time('schedule_in');
            $table->time('schedule_out');
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
        Schema::dropIfExists('overtime_shift_employees');
    }
}
