<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncidentPenaltiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incident_penalties', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('interrogation_report_actor_id');
            $table->integer('penalty')->default('0');
            $table->integer('installment_count')->default('0');
            $table->boolean('type_of_installment')->default(false);
            $table->integer('period_of_time')->default('0');
            $table->string('type_of_period')->nullable();
            $table->date('early_payment')->nullable();
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
        Schema::dropIfExists('incident_penalties');
    }
}
