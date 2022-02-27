<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProjectsToUserPermissionsTable extends Migration
{
    public function up()
    {
        Schema::table('user_permissions', function (Blueprint $table) {
            $table->boolean('can_see_projects')->nullable();
        });
    }

    public function down()
    {
        Schema::table('user_permissions', function (Blueprint $table) {
            //
        });
    }
}
