<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('swapi_film_vehicle', function (Blueprint $table) {
            $table->foreignId('film_id')->constrained('swapi_films')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained('swapi_vehicles')->cascadeOnDelete();
            $table->primary(['film_id', 'vehicle_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('swapi_film_vehicle');
    }
};
