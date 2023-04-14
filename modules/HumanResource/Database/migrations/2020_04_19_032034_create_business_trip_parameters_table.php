<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessTripParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_trip_parameters', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('business_trip_id');
            $table->unsignedInteger('receipt_parameter_id');
            $table->integer('cost');
            $table->string('image', 50)->nullable();
            $table->string('dimensions', 50)->nullable();
            $table->string('path', 50)->nullable();
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
        Schema::dropIfExists('business_trip_parameters');
    }
}
