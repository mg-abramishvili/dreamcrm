<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPreRubToCatalogItemsTable extends Migration
{
    public function up()
    {
        Schema::table('catalog_items', function (Blueprint $table) {
            $table->decimal('pre_rub')->nullable();
            $table->decimal('pre_usd')->nullable();
        });
    }

    public function down()
    {
        Schema::table('catalog_items', function (Blueprint $table) {
            //
        });
    }
}
