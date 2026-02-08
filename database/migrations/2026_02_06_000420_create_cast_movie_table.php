<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cast_movie', function (Blueprint $table) {
            $table->foreignId('cast_id')->constrained()->cascadeOnDelete();
            $table->foreignId('movie_id')->constrained()->cascadeOnDelete();
            $table->string('role_name')->nullable();
            $table->primary(['cast_id', 'movie_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cast_movie');
    }
};
