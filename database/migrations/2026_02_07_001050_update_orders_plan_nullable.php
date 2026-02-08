<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            Schema::disableForeignKeyConstraints();

            Schema::table('orders', function (Blueprint $table) {
                if (Schema::hasColumn('orders', 'plan_id')) {
                    $table->dropForeign(['plan_id']);
                    $table->dropColumn('plan_id');
                }
            });

            Schema::table('orders', function (Blueprint $table) {
                $table->foreignId('plan_id')->nullable()->constrained()->nullOnDelete();
            });

            Schema::enableForeignKeyConstraints();

            return;
        }

        Schema::disableForeignKeyConstraints();

        Schema::create('orders_new', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('USD');
            $table->string('status')->default('pending');
            $table->string('provider')->default('pesapal');
            $table->string('provider_reference')->nullable();
            $table->string('checkout_url')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'status']);
        });

        \DB::statement('INSERT INTO orders_new (id, user_id, plan_id, amount, currency, status, provider, provider_reference, checkout_url, meta, created_at, updated_at)
            SELECT id, user_id, plan_id, amount, currency, status, provider, provider_reference, checkout_url, meta, created_at, updated_at FROM orders');

        Schema::drop('orders');
        Schema::rename('orders_new', 'orders');

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            Schema::disableForeignKeyConstraints();

            Schema::table('orders', function (Blueprint $table) {
                $table->dropForeign(['plan_id']);
                $table->dropColumn('plan_id');
            });

            Schema::table('orders', function (Blueprint $table) {
                $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            });

            Schema::enableForeignKeyConstraints();

            return;
        }

        Schema::disableForeignKeyConstraints();

        Schema::create('orders_new', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('USD');
            $table->string('status')->default('pending');
            $table->string('provider')->default('pesapal');
            $table->string('provider_reference')->nullable();
            $table->string('checkout_url')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'status']);
        });

        \DB::statement('INSERT INTO orders_new (id, user_id, plan_id, amount, currency, status, provider, provider_reference, checkout_url, meta, created_at, updated_at)
            SELECT id, user_id, plan_id, amount, currency, status, provider, provider_reference, checkout_url, meta, created_at, updated_at FROM orders');

        Schema::drop('orders');
        Schema::rename('orders_new', 'orders');

        Schema::enableForeignKeyConstraints();
    }
};
