<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskBoardUserTable extends Migration
{
    public function up()
    {
        Schema::create('task_board__user', function (Blueprint $table) {
            $table->id();
            $table->integer('task_board_id');
            $table->integer('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('task_board__user');
    }
}
