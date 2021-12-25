<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CalculationElementTable extends Migration
{
    public function up()
    {
        Schema::create('calculation_element', function (Blueprint $table) {
            $table->id();
            $table->integer('calculation_id');
            $table->integer('element_id');
            $table->decimal('price');
        });
    }

    public function down()
    {
        Schema::dropIfExists('calculation_element');
    }
}
