<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('stream_tokens', 'session_id') && Schema::getConnection()->getDriverName() !== 'sqlite') {
            return;
        }

        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            Schema::table('stream_tokens', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });

            Schema::table('stream_tokens', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
                $table->string('session_id')->nullable()->index();
                $table->index(['user_id', 'expires_at']);
            });

            return;
        }

        Schema::disableForeignKeyConstraints();

        Schema::create('stream_tokens_new', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('session_id')->nullable()->index();
            $table->foreignId('video_file_id')->constrained('video_files')->cascadeOnDelete();
            $table->string('token')->unique();
            $table->timestamp('expires_at');
            $table->unsignedInteger('uses_remaining')->default(1);
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'expires_at']);
        });

        \DB::statement('INSERT INTO stream_tokens_new (id, user_id, video_file_id, token, expires_at, uses_remaining, used_at, created_at, updated_at)
            SELECT id, user_id, video_file_id, token, expires_at, uses_remaining, used_at, created_at, updated_at FROM stream_tokens');

        Schema::drop('stream_tokens');
        Schema::rename('stream_tokens_new', 'stream_tokens');

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            Schema::table('stream_tokens', function (Blueprint $table) {
                $table->dropIndex(['user_id', 'expires_at']);
                $table->dropColumn('session_id');
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });

            Schema::table('stream_tokens', function (Blueprint $table) {
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->index(['user_id', 'expires_at']);
            });

            return;
        }

        Schema::disableForeignKeyConstraints();

        Schema::create('stream_tokens_new', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('video_file_id')->constrained('video_files')->cascadeOnDelete();
            $table->string('token')->unique();
            $table->timestamp('expires_at');
            $table->unsignedInteger('uses_remaining')->default(1);
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'expires_at']);
        });

        \DB::statement('INSERT INTO stream_tokens_new (id, user_id, video_file_id, token, expires_at, uses_remaining, used_at, created_at, updated_at)
            SELECT id, user_id, video_file_id, token, expires_at, uses_remaining, used_at, created_at, updated_at FROM stream_tokens');

        Schema::drop('stream_tokens');
        Schema::rename('stream_tokens_new', 'stream_tokens');

        Schema::enableForeignKeyConstraints();
    }
};
