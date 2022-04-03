<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionsTable extends Migration
{
    public function up()
    {
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->integer('project_id');
            $table->integer('user_id')->nullable();
            $table->string('name');
            $table->string('priority')->default('normal');
            $table->string('status')->default('new');
            $table->string('invoice_number')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('activation_key')->nullable();
            $table->string('supply_info')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->text('description')->nullable();
            $table->text('ral')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('productions');
    }
}
