<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('work_order_action_id');
            $table->unsignedInteger('machine_group_id')->nullable();
            $table->unsignedInteger('service_type_id')->nullable();
            $table->unsignedInteger('department_id')->nullable();
            $table->tinyInteger('source');
            $table->tinyInteger('type');
            $table->string('code');
            $table->text('description')->nullable();
            $table->boolean('is_urgent')->default(true);
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
        Schema::dropIfExists('work_orders');
    }
}
