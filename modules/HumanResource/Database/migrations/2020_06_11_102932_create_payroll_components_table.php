<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_components', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('payroll_component_type_id');
            $table->string('status', 20);
            $table->string('time', 20);
            $table->string('code', 50);
            $table->string('name', 50);
            $table->date('effective_date');
            $table->boolean('taxable')->default(false);
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
        Schema::dropIfExists('payroll_components');
    }
}
