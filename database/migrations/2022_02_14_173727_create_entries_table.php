<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('appears_home')->default(0);
            $table->integer('index_content')->default(0);
            $table->integer('likes_number')->default(0);
            $table->integer('views_number')->default(0);
            $table->integer('reading_time')->default(0);
            $table->unsignedBigInteger('status_id');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('author_id');
            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('theme_id');
            $table->foreign('theme_id')->references('id')->on('themes')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('entry_type_id');
            $table->foreign('entry_type_id')->references('id')->on('entry_types')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entries');
    }
}
