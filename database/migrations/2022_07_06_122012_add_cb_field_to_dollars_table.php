<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCbFieldToDollarsTable extends Migration
{
    public function up()
    {
        Schema::table('dollars', function (Blueprint $table) {
            $table->decimal('cb')->default(0);
            $table->integer('additional')->default(0);
        });
    }

    public function down()
    {
        Schema::table('dollars', function (Blueprint $table) {
            //
        });
    }
}
