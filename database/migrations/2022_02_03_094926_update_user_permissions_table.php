<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserPermissionsTable extends Migration
{
    public function up()
    {
        Schema::table('user_permissions', function (Blueprint $table) {
            $table->boolean('can_see_calculations')->nullable();
            $table->boolean('can_create_calculations')->nullable();
            $table->boolean('can_see_catalog')->nullable();
            $table->boolean('can_edit_catalog')->nullable();
            $table->boolean('can_see_offers')->nullable();
            $table->boolean('can_create_offers')->nullable();
            $table->boolean('can_see_projects')->nullable();
            $table->boolean('can_create_projects')->nullable();
            $table->boolean('can_see_clients')->nullable();
            $table->boolean('can_create_clients')->nullable();
        });
    }

    public function down()
    {
        //
    }
}
