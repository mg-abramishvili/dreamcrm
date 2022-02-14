<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalculationsTable extends Migration
{
    public function up()
    {
        Schema::create('calculations', function (Blueprint $table) {
            $table->id();
            $table->integer('catalog_type_id');
            $table->decimal('price');
            $table->integer('quantity');
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('calculations');
    }
}
