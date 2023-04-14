<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBpjsKetenagakerjaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bpjs_ketenagakerjaans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('card_number');
            $table->string('jkk');
            $table->string('jkm');
            $table->string('pk_tk');
            $table->string('tk_tk');
            $table->string('pk_jp');
            $table->string('tk_jp');
            $table->date('tk_date');
            $table->date('jp_date');
            $table->integer('month');
            $table->integer('year');
            $table->string('office');
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
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
        Schema::dropIfExists('bpjs_ketenagakerjaans');
    }
}
