<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPermissionBoardsTable extends Migration
{
    public function up()
    {
        Schema::create('user_permission_boards', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->boolean('can_see_all_boards')->nullable();
            $table->boolean('can_delete_all_boards')->nullable();
            $table->boolean('can_create_board')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_permission_boards');
    }
}
