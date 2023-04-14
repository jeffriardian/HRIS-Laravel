<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfficeHourSpecialDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_hour_special_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('office_hour_special_id');
            $table->time('weekday_in');
            $table->time('weekday_out');
            $table->time('weekend_in');
            $table->time('weekend_out');
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
        Schema::dropIfExists('office_hour_special_details');
    }
}
