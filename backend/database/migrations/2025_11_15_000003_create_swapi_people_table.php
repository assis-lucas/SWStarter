<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('swapi_people', function (Blueprint $table) {
            $table->id();
            $table->integer('swapi_id')->unique();
            $table->string('name');
            $table->string('height')->nullable();
            $table->string('mass')->nullable();
            $table->string('hair_color')->nullable();
            $table->string('skin_color')->nullable();
            $table->string('eye_color')->nullable();
            $table->string('birth_year')->nullable();
            $table->string('gender')->nullable();
            $table->foreignId('homeworld_id')->nullable()->constrained('swapi_planets')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('swapi_people');
    }
};
