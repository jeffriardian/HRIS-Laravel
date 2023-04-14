<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('menu_id');
            $table->unsignedInteger('role_id')->nullable();
            $table->unsignedInteger('permission_id')->nullable();
            $table->unsignedInteger('parent_id')->nullable()->default(0);
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('url')->nullable();
            $table->string('target')->default('_self');
            $table->integer('order')->unsigned()->default(0);
            $table->string('route')->nullable();
            $table->text('params')->nullable();
            $table->string('controller')->nullable();
            $table->string('middleware')->nullable();
            $table->string('icon')->nullable();
            $table->text('option')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned();
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
        Schema::dropIfExists('menu_items');
    }
}
