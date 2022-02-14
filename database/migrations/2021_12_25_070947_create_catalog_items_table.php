<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogItemsTable extends Migration
{
    public function up()
    {
        Schema::create('catalog_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('category_id');
            $table->decimal('pre_rub')->default(0);
            $table->decimal('pre_usd')->default(0);
            $table->decimal('price')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('catalog_items');
    }
}
