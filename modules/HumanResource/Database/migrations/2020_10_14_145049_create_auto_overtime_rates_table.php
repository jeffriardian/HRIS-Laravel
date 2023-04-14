<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutoOvertimeRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_overtime_rates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employee_id');
            $table->date('date');
            $table->float('total_times');
            $table->float('L1')->nullable();
            $table->integer('rates_L1')->nullable();
            $table->float('L2')->nullable();
            $table->integer('rates_L2')->nullable();
            $table->float('L3')->nullable();
            $table->integer('rates_L3')->nullable();
            $table->integer('total_rates');
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
        Schema::dropIfExists('auto_overtime_rates');
    }
}
