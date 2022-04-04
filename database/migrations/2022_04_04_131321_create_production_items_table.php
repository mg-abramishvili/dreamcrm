<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionItemsTable extends Migration
{
    public function up()
    {
        Schema::create('production_items', function (Blueprint $table) {
            $table->id();
            $table->integer('production_id');
            $table->integer('stock_item_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('production_items');
    }
}
