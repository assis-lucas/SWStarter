<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('query_logs', function (Blueprint $table) {
            $table->id();
            $table->text('sql');
            $table->text('bindings');
            $table->string('duration_ms');
            $table->longText('full_query');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('query_logs');
    }
};
