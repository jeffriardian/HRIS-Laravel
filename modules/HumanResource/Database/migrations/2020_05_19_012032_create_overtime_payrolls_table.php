<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOvertimePayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtime_payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('employee_id');
            $table->date('date_overtime');
            $table->string('schedule_start_overtime', 30);
            $table->string('start_overtime', 30);
            $table->string('schedule_end_overtime', 30);
            $table->string('end_overtime', 30);
            $table->integer('overtime_1');
            $table->integer('amount_overtime_1');
            $table->integer('total_overtime_1');
            $table->integer('overtime_2');
            $table->integer('amount_overtime_2');
            $table->integer('total_overtime_2');
            $table->integer('overtime_weekend_2');
            $table->integer('amount_overtime_weekend_2');
            $table->integer('total_overtime_weekend_2');
            $table->integer('overtime_weekend_3');
            $table->integer('amount_overtime_weekend_3');
            $table->integer('total_overtime_weekend_3');
            $table->integer('total_overtime');
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
        Schema::dropIfExists('overtime_payrolls');
    }
}
