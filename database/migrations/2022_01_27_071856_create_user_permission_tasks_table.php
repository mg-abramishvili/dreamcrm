<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPermissionTasksTable extends Migration
{
    public function up()
    {
        Schema::create('user_permission_tasks', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->boolean('can_see_all_tasks')->nullable();
            $table->boolean('can_delete_all_tasks')->nullable();
            $table->boolean('can_assign_tasks_to_users')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_permission_tasks');
    }
}
