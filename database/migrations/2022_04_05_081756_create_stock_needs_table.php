<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockNeedsTable extends Migration
{
    public function up()
    {
        Schema::create('stock_needs', function (Blueprint $table) {
            $table->id();
            $table->integer('production_item_id')->nullable();
            $table->integer('stock_item_id');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_needs');
    }
}
