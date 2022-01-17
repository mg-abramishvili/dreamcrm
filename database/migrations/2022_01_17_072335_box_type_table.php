<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BoxTypeTable extends Migration
{
    public function up()
    {
        Schema::create('box_type', function (Blueprint $table) {
            $table->id();
            $table->integer('box_id');
            $table->integer('type_id');
        });
    }

    public function down()
    {
        //
    }
}
