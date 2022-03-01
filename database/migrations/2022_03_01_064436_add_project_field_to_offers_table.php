<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProjectFieldToOffersTable extends Migration
{
    public function up()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->integer('project_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('offers', function (Blueprint $table) {
            //
        });
    }
}
