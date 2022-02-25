<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPreRubToCalculationsTable extends Migration
{
    public function up()
    {
        Schema::table('calculations', function (Blueprint $table) {
            $table->integer('type_id')->nullable();
            $table->decimal('pre_rub')->nullable();
            $table->decimal('pre_usd')->nullable();
        });
    }

    public function down()
    {
        Schema::table('calculations', function (Blueprint $table) {
            //
        });
    }
}
