<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPdfFieldToOffersTable extends Migration
{
    public function up()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->string('pdf')->nullable();
        });
    }

    public function down()
    {
        Schema::table('offers', function (Blueprint $table) {
            //
        });
    }
}
