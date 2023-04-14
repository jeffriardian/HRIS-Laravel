<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() 
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            // $table->unsignedInteger('department_id');
            $table->unsignedInteger('position_id');
            $table->unsignedInteger('religion_id')->nullable();
            // $table->unsignedInteger('blood_type_id');
            $table->unsignedInteger('marital_status_id')->nullable();
            $table->unsignedInteger('company_id');
            $table->string('photo')->nullable();
            $table->string('ktp', 20)->nullable();
            $table->string('name')->nullable();
            $table->string('gender')->nullable();
            $table->text('address')->nullable();
            $table->string('post_code')->nullable();
            $table->string('birth_place')->nullable();
            $table->date('birth_at')->nullable();
            $table->string('has_sim')->nullable();
            $table->string('sim')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('achivement')->nullable();
            $table->string('thesis_title')->nullable();
            $table->integer('last_salary')->nullable();
            $table->string('last_facility')->nullable();
            $table->text('achivement_during_work')->nullable();
            $table->string('last_position')->nullable();
            $table->text('last_job_desc')->nullable();
            $table->text('applying_reason')->nullable();
            $table->string('work_environment')->nullable();
            $table->integer('expected_salaries')->nullable();
            $table->string('expected_facilities')->nullable();
            $table->string('work_out_of_town')->nullable();
            $table->string('placed_out_of_town')->nullable();
            $table->string('work_accident')->nullable();
            $table->date('date_of_accident')->nullable();
            $table->string('psychological_check')->nullable();
            $table->date('date_of_check')->nullable();
            $table->string('check_place')->nullable();
            $table->string('check_purpose')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->string('vehicle_belong')->nullable();
            $table->string('employees_name')->nullable();
            $table->string('employees_relationship')->nullable();
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
        Schema::dropIfExists('candidates');
    }
}
