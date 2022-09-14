<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWikipediaCachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wikipedia_caches', function (Blueprint $table) {
            $table->increments('id');

            $table->string('link')->unique();
            $table->string('heading');
            $table->longText('content');
            $table->bigInteger('count');
            $table->dateTime('cached_at');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wikipedia_caches');
    }
}
