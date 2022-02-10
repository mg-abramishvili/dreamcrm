<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPermissionsTable extends Migration
{
    public function up()
    {
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->boolean('can_see_all_boards')->nullable();
            $table->boolean('can_see_calculations')->nullable();
            $table->boolean('can_see_catalog')->nullable();
            $table->boolean('can_edit_catalog')->nullable();
            $table->boolean('can_see_all_projects')->nullable();
            $table->boolean('can_see_all_clients')->nullable();
            $table->boolean('can_see_users')->nullable();
            $table->boolean('can_add_users')->nullable();
            $table->boolean('can_see_stock')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_permissions');
    }
}
