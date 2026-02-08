<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->foreignId('language_id')->nullable()->after('language')->constrained('languages')->nullOnDelete();
        });

        Schema::table('series', function (Blueprint $table) {
            $table->foreignId('language_id')->nullable()->after('language')->constrained('languages')->nullOnDelete();
        });

        Schema::table('episodes', function (Blueprint $table) {
            $table->foreignId('language_id')->nullable()->after('language')->constrained('languages')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('episodes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('language_id');
        });

        Schema::table('series', function (Blueprint $table) {
            $table->dropConstrainedForeignId('language_id');
        });

        Schema::table('movies', function (Blueprint $table) {
            $table->dropConstrainedForeignId('language_id');
        });
    }
};
