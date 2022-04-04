<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservesTable extends Migration
{
    public function up()
    {
        Schema::create('reserves', function (Blueprint $table) {
            $table->id();
            $table->integer('production_item_id');
            $table->integer('stock_balance_id');
            $table->integer('pre_rub')->nullable();
            $table->integer('pre_usd')->nullable();
            $table->integer('price');
            $table->integer('quantity');
            $table->integer('price_total');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reserves');
    }
}
