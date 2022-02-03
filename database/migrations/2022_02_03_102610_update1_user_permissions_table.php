<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Update1UserPermissionsTable extends Migration
{
    public function up()
    {
        Schema::table('user_permissions', function (Blueprint $table) {
            $table->boolean('can_see_users')->nullable();
            $table->boolean('can_add_users')->nullable();
        });
    }

    public function down()
    {
        //
    }
}
