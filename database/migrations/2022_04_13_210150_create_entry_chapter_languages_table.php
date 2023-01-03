<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntryChapterLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entry_chapter_languages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('entry_chapter_id');
            $table->foreign('entry_chapter_id')->references('id')->on('entry_chapters')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('language_id');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade')->onUpdate('cascade');
            $table->string('title', 191);
            $table->longText('content');
            $table->text('slug')->nullable();
            $table->string('url_video', 191);
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
        Schema::dropIfExists('entry_chapter_languages');
    }
}
