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
            $table->boolean('can_see_all_tasks')->nullable();
            $table->boolean('can_see_all_boards')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_permissions');
    }
}
