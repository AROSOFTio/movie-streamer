<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('series_vj', function (Blueprint $table) {
            $table->id();
            $table->foreignId('series_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vj_id')->constrained('vjs')->cascadeOnDelete();
            $table->unique(['series_id', 'vj_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('series_vj');
    }
};
