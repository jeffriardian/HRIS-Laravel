<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessTripImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_trip_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('business_trip_id');
            $table->unsignedBigInteger('payment_slip_id');
            $table->char('payment_slip_type', 1)->comment('0: parameter, 1: transportation');
            $table->string('image');
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
        Schema::dropIfExists('business_trip_images');
    }
}
