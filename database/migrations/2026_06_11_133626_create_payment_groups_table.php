<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_groups', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('invoice_code')->unique();

            $table->enum('payment_method', [
                'QRIS',
                'Cash',
            ])->default('QRIS');

            $table->integer('total_price')->default(0);

            $table->enum('status', [
                'pending',
                'paid',
                'cancelled',
            ])->default('pending');

            $table->timestamp('cash_deadline')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_groups');
    }
};