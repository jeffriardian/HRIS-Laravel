<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePphsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pphs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('employee_id');
            $table->integer('salary');
            $table->integer('company_allowance');
            $table->integer('bruto');
            $table->integer('deduction');
            $table->integer('netto_month');
            $table->integer('netto_year');
            $table->integer('ptkp');
            $table->integer('pkp');
            $table->integer('pph21_owed_year');
            $table->integer('pph21_owed_month');
            $table->string('month_period',5);
            $table->string('year_period',5);
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
        Schema::dropIfExists('pphs');
    }
}
