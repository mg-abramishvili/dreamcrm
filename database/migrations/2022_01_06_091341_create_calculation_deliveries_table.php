<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalculationDeliveriesTable extends Migration
{
    public function up()
    {
        Schema::create('calculation_deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('fixed');
            $table->decimal('price')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('calculation_deliveries');
    }
}
