<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntryLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entry_languages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('entry_id');
            $table->foreign('entry_id')->references('id')->on('entries')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('language_id');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade')->onUpdate('cascade');
            $table->string('title', 191);
            $table->string('subtitle', 191)->nullable();
            $table->longText('video_transcription');
            $table->longText('content');
            $table->text('meta_description');
            $table->text('seo_title');
            $table->text('slug')->nullable();
            $table->longText('h1')->nullable();
            $table->longText('h2')->nullable();
            $table->longText('h3')->nullable();
            $table->longText('h4')->nullable();
            $table->string('url_video_youtube', 191)->nullable();
            $table->string('url_video_vimeo', 191)->nullable();
            $table->string('url_audio', 191)->nullable();
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
        Schema::dropIfExists('entry_languages');
    }
}
