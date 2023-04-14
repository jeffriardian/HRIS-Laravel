<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDummyAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dummy_attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('employee_code');
            $table->dateTime('scan_in');
            $table->dateTime('scan_out');
            $table->unsignedBigInteger('shift_id');
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
        Schema::dropIfExists('dummy_attendances');
    }
}
