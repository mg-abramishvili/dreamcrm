<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogBoxStockItemTable extends Migration
{
    public function up()
    {
        Schema::create('catalog_box__stock_item', function (Blueprint $table) {
            $table->id();
            $table->integer('catalog_box_id');
            $table->integer('stock_item_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('catalog_box__stock_item');
    }
}
