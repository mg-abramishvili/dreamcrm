<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommentFieldToCalculationsTable extends Migration
{
    public function up()
    {
        Schema::table('calculations', function (Blueprint $table) {
            $table->text('comment')->nullable();
        });
    }

    public function down()
    {
        Schema::table('calculations', function (Blueprint $table) {
            //
        });
    }
}
