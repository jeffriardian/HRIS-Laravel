<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOvertimeHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtime_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('overtime_id');
            $table->string('overtime_before_duration')->nullable();
            $table->string('overtime_after_duration')->nullable();
            $table->string('total_time');
            $table->boolean('approved_status')->nullable();
            $table->text('reject_description')->nullable();
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
        Schema::dropIfExists('overtime_histories');
    }
}
