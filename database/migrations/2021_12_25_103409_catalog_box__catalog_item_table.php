<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CatalogBoxCatalogItemTable extends Migration
{
    public function up()
    {
        Schema::create('catalog_box__catalog_item', function (Blueprint $table) {
            $table->id();
            $table->integer('catalog_box_id');
            $table->integer('catalog_item_id');
        });
    }


    public function down()
    {
        Schema::dropIfExists('catalog_box__catalog_item');
    }
}
