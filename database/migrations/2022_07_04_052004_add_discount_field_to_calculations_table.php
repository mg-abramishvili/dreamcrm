<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountFieldToCalculationsTable extends Migration
{
    public function up()
    {
        Schema::table('calculations', function (Blueprint $table) {
            $table->integer('discount')->default(0);
        });
    }

    public function down()
    {
        Schema::table('calculations', function (Blueprint $table) {
            //
        });
    }
}
