<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('department_id');
            $table->unsignedInteger('position_id');
            $table->unsignedInteger('religion_id');
            $table->unsignedInteger('blood_type_id');
            $table->unsignedInteger('marital_status_id');
            $table->unsignedInteger('employee_status_id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('office_hour_id');
            $table->unsignedInteger('payroll_type_id');
            $table->unsignedInteger('salary_type_id');
            $table->unsignedInteger('day_off_id');
            $table->string('ptkp_code')->nullable();
            $table->string('nik')->unique();
            $table->string('old_nik')->nullable();
            $table->string('ktp', 20)->nullable();
            $table->string('kk', 20)->nullable();
            $table->string('npwp', 25)->nullable();
            $table->string('sim', 12)->nullable();
            $table->string('pin')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->text('address_ktp')->nullable();
            $table->string('photo')->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->string('birth_place')->nullable();
            $table->date('birth_at')->nullable();
            $table->date('join_at')->nullable();
            $table->date('leave_at')->nullable();
            $table->float('auto_overtime')->nullable();
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
        Schema::dropIfExists('employees');
    }
}
