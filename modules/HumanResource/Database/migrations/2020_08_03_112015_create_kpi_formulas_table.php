<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKpiFormulasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpi_formulas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('assesment_point');
            $table->string('unit_of_measurement');
            $table->string('target');
            $table->string('numerator');
            $table->string('denominator');
            $table->string('percentage_of_formula');
            $table->string('formula_code');
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('kpi_formulas');
    }
}
