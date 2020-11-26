<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediableTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('media')) {
            Schema::create(
                'media',
                function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('disk', 32);
                    $table->string('directory');
                    $table->string('filename');
                    $table->string('extension', 32);
                    $table->string('mime_type', 128);
                    $table->string('aggregate_type', 32);
                    $table->unsignedBigInteger('size')->unsigned();
                    $table->timestamps();

                    $table->unique(['disk', 'directory', 'filename', 'extension']);
                    $table->index('aggregate_type');
                }
            );
        }

        if (!Schema::hasTable('mediables')) {
            Schema::create(
                'mediables',
                function (Blueprint $table) {
                    $table->bigIncrements('media_id')->unsigned();
                    $table->string('mediable_type');
                    $table->unsignedBigInteger('mediable_id')->unsigned();
                    $table->string('tag');
                    $table->unsignedBigInteger('order')->unsigned();

                    // $table->primary(['media_id', 'mediable_type', 'mediable_id', 'tag']);


                    $table->index(['mediable_id', 'mediable_type']);
                    $table->index('tag');
                    $table->index('order');
                    $table->foreign('media_id')
                        ->references('id')->on('media')
                        ->cascadeOnDelete();
                }
            );
            DB::unprepared('ALTER TABLE `mediables` DROP PRIMARY KEY, ADD PRIMARY KEY (  `media_id`, `mediable_type`, `mediable_id`, `tag` )');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mediables');
        Schema::dropIfExists('media');
    }
}
