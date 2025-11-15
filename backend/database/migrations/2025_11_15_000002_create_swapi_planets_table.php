<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('swapi_planets', function (Blueprint $table) {
            $table->id();
            $table->integer('swapi_id')->unique();
            $table->string('name');
            $table->string('rotation_period')->nullable();
            $table->string('orbital_period')->nullable();
            $table->string('diameter')->nullable();
            $table->string('climate')->nullable();
            $table->string('gravity')->nullable();
            $table->string('terrain')->nullable();
            $table->string('surface_water')->nullable();
            $table->string('population')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('swapi_planets');
    }
};
