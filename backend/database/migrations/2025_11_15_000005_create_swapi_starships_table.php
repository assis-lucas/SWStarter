<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('swapi_starships', function (Blueprint $table) {
            $table->id();
            $table->integer('swapi_id')->unique();
            $table->string('name');
            $table->string('model')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('cost_in_credits')->nullable();
            $table->string('length')->nullable();
            $table->string('max_atmosphering_speed')->nullable();
            $table->string('crew')->nullable();
            $table->string('passengers')->nullable();
            $table->string('cargo_capacity')->nullable();
            $table->string('consumables')->nullable();
            $table->string('hyperdrive_rating')->nullable();
            $table->string('MGLT')->nullable();
            $table->string('starship_class')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('swapi_starships');
    }
};
