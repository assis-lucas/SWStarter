<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('swapi_films', function (Blueprint $table) {
            $table->id();
            $table->integer('swapi_id')->unique();
            $table->string('title');
            $table->integer('episode_id');
            $table->text('opening_crawl');
            $table->string('director');
            $table->string('producer');
            $table->date('release_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('swapi_films');
    }
};
