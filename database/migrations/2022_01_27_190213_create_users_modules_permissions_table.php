<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersModulesPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_modules_permissions', function (Blueprint $table) {

		$table->bigIncrements('id')->unsigned();
		$table->unsignedBigInteger('module_id');
		$table->unsignedBigInteger('permission_id');
		$table->unsignedBigInteger('users_id');
        $table->foreign('module_id')
            ->references('id')
            ->on('modules')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        $table->foreign('permission_id')
            ->references('id')
            ->on('permissions')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        $table->foreign('users_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users_modules_permissions');
    }
}
