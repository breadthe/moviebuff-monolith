<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->string('id', 10)->primary()->comment('imdbID');
            $table->string('title');
            $table->string('poster');
            $table->string('type', 10);
            $table->integer('year');
            $table->timestamps();
        });

        Schema::create('movie_catalog', function (Blueprint $table) {
            $table->string('movie_id', 10)->comment('imdbID');
            $table->integer('catalog_id');
            $table->primary(['movie_id', 'catalog_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies');
        Schema::dropIfExists('movie_catalog');
    }
}
