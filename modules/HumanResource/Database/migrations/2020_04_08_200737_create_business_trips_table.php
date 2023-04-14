<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_trips', function (Blueprint $table) {
            $table->id();
            $table->string('business_trip_number');
            $table->string('receipt_number');
            $table->unsignedInteger('employee_id');
            $table->date('departure_date')->nullable();
            $table->date('back_date')->nullable();
            $table->string('destination')->nullable();
            $table->text('purpose')->nullable();
            $table->integer('total_cost');
            $table->unsignedInteger('approval_status_id')->default('1');
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
        Schema::dropIfExists('business_trips');
    }
}
