<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('task_comments', function (Blueprint $table) {
            $table->id();
            $table->integer('task_id');
            $table->integer('user_id');
            $table->text('text');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
