<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReimbursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reimburses', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('reimburse_category_id');
            $table->unsignedInteger('employee_id');
            $table->string('reimburse_number', 255);
            $table->string('description', 255)->nullable();
            $table->integer('total_cost');
            $table->string('reimburse_type', 10)->nullable();
            $table->unsignedInteger('approval_status_id')->default('1');
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
        Schema::dropIfExists('reimburses');
    }
}
