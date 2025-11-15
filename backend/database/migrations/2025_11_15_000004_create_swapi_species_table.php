<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('swapi_species', function (Blueprint $table) {
            $table->id();
            $table->integer('swapi_id')->unique();
            $table->string('name');
            $table->string('classification')->nullable();
            $table->string('designation')->nullable();
            $table->string('average_height')->nullable();
            $table->string('skin_colors')->nullable();
            $table->string('hair_colors')->nullable();
            $table->string('eye_colors')->nullable();
            $table->string('average_lifespan')->nullable();
            $table->string('language')->nullable();
            $table->foreignId('homeworld_id')->nullable()->constrained('swapi_planets')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('swapi_species');
    }
};
