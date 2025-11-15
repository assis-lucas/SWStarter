<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('swapi_film_person', function (Blueprint $table) {
            $table->foreignId('film_id')->constrained('swapi_films')->cascadeOnDelete();
            $table->foreignId('person_id')->constrained('swapi_people')->cascadeOnDelete();
            $table->primary(['film_id', 'person_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('swapi_film_person');
    }
};
