<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCooperativeMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cooperative_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('department_id');
            $table->unsignedInteger('employee_id');
            $table->unsignedInteger('cooperative_member_type_id');
            $table->string('id_member')->unique();
            $table->string('name');
            $table->date('join_date');
            $table->date('end_date')->default(NULL);
            $table->integer('saving_balance')->default('0');
            $table->integer('loan_balance')->default('0');
            $table->integer('installment_count')->default('0');
            $table->integer('installment_payment')->default('0');
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
        Schema::dropIfExists('cooperative_members');
    }
}
