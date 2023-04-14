<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeTypeCooperativesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_type_cooperatives', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
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
        Schema::dropIfExists('employee_type_cooperatives');
    }
}
