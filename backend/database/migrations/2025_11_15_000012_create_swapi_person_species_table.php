<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('swapi_person_species', function (Blueprint $table) {
            $table->foreignId('person_id')->constrained('swapi_people')->cascadeOnDelete();
            $table->foreignId('specie_id')->constrained('swapi_species')->cascadeOnDelete();
            $table->primary(['person_id', 'specie_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('swapi_person_species');
    }
};
