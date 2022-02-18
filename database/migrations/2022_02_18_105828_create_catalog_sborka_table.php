<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogSborkaTable extends Migration
{
    public function up()
    {
        Schema::create('catalog_sborka', function (Blueprint $table) {
            $table->id();
            $table->integer('arenda');
            $table->integer('person');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('catalog_sborka');
    }
}
