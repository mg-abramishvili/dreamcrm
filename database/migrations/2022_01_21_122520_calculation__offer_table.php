<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CalculationOfferTable extends Migration
{
    public function up()
    {
        Schema::create('calculation__offer', function (Blueprint $table) {
            $table->id();
            $table->integer('calculation_id');
            $table->integer('offer_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('calculation__offer');
    }
}
