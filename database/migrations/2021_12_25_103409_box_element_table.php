<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BoxElementTable extends Migration
{
    public function up()
    {
        Schema::create('box_element', function (Blueprint $table) {
            $table->id();
            $table->integer('box_id');
            $table->integer('element_id');
        });
    }


    public function down()
    {
        Schema::dropIfExists('box_element');
    }
}
