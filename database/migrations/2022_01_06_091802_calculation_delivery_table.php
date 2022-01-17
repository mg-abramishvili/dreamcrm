<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CalculationDeliveryTable extends Migration
{
    public function up()
    {
        Schema::create('calculation_delivery', function (Blueprint $table) {
            $table->id();
            $table->integer('calculation_id');
            $table->integer('delivery_id');
            $table->string('direction_from')->nullable();
            $table->string('direction_to')->nullable();
            $table->string('days')->nullable();
            $table->decimal('price')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('calculation_delivery');
    }
}
