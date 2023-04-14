<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('payroll_period_id');
            $table->unsignedInteger('employee_id');
            $table->integer('salary')->default('0');
            $table->integer('allowance')->default('0');
            $table->integer('deduction')->default('0');
            $table->integer('benefit')->default('0');
            $table->integer('bruto')->default('0');
            $table->integer('netto')->default('0');
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
        Schema::dropIfExists('payrolls');
    }
}
