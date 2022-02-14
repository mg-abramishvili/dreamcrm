<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CalculationCatalogBoxTable extends Migration
{
    public function up()
    {
        Schema::create('calculation__catalog_box', function (Blueprint $table) {
            $table->id();
            $table->integer('calculation_id');
            $table->integer('catalog_box_id');
            $table->decimal('pre_rub');
            $table->decimal('pre_usd');
            $table->decimal('sborka');
            $table->decimal('marzha');
            $table->decimal('price');
        });
    }

    public function down()
    {
        Schema::dropIfExists('calculation__catalog_box');
    }
}
