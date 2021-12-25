<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoxesTable extends Migration
{
    public function up()
    {
        Schema::create('boxes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('pre_rub')->default(0);
            $table->decimal('pre_usd')->default(0);
            $table->decimal('price')->default(0);
            $table->longText('description')->nullable();
            $table->longText('manager_description')->nullable();
            $table->longtext('gallery')->nullable();
            $table->decimal('length')->nullable();
            $table->decimal('width')->nullable();
            $table->decimal('height')->nullable();
            $table->decimal('weight')->nullable();
            $table->decimal('sborka')->nullable();
            $table->decimal('marzha')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('boxes');
    }
}
