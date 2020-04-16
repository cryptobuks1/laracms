<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->string('extension', 20);
            $table->integer('size')->default(0);
            $table->string('mime_type', 20);
            $table->string('type', 20);
            $table->string('url', 250);
            $table->string('source', 250);
            $table->string('alt', 250)->nullable();
            $table->text('description')->nullable();
            $table->string('location', 100)->nullable();
            $table->string('folder', 50)->nullable();
            $table->bigInteger('author')->default(0);
            $table->timestamp('created_at')->nullable();
            $table->index(['name', 'type', 'url', 'folder', 'author']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media');
    }
}
