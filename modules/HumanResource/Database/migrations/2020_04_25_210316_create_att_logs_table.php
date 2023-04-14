<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('att_logs', function (Blueprint $table) {
            $table->id();
            $table->string('sn', 30);
            $table->string('scan_date', 30);
            $table->string('pin', 32);
            $table->integer('verifymode');
            $table->integer('inoutmode');
            $table->integer('reserved');
            $table->integer('work_code');
            $table->string('att_id', 1);
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
        Schema::dropIfExists('att_logs');
    }
}
