<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskBoardColumnsTable extends Migration
{
    public function up()
    {
        Schema::create('task_board_columns', function (Blueprint $table) {
            $table->id();
            $table->integer('board_id');
            $table->string('name');
            $table->integer('order');
        });
    }

    public function down()
    {
        Schema::dropIfExists('task_board_columns');
    }
}
