<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInterrogationReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interrogation_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->unsignedBigInteger('incident_category_id');
            $table->date('date_of_incident')->nullable();
            $table->text('incident_chronologic')->nullable();
            $table->integer('lost_cost')->default('0')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('attachment')->nullable();
            $table->unsignedInteger('interrogation_report_type_id')->nullable();
            $table->string('typeable_type')->nullable();
            $table->string('typeable_id')->nullable();
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
        Schema::dropIfExists('interrogation_reports');
    }
}
