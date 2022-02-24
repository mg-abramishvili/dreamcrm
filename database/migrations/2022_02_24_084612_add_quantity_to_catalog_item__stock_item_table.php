<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuantityToCatalogItemStockItemTable extends Migration
{
    public function up()
    {
        Schema::table('catalog_item__stock_item', function (Blueprint $table) {
            $table->integer('quantity')->nullable();
        });
    }

    public function down()
    {
        Schema::table('catalog_item__stock_item', function (Blueprint $table) {
            //
        });
    }
}
