<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskBoardsTable extends Migration
{
    public function up()
    {
        Schema::create('task_boards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('admin');
            $table->boolean('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('task_boards');
    }
}
