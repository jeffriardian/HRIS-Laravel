<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBpjsKesehatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bpjs_kesehatans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('card_number');
            $table->string('employee_salary_deduction');
            $table->string('office_salary_deduction');
            $table->string('date');
            $table->string('office');
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
        Schema::dropIfExists('bpjs_kesehatans');
    }
}
