<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->foreignId('kid_id')->constrained()->onDelete('cascade');

            $table->string('package_name');

            $table->integer('price');

            $table->enum('status', [
                'pending',
                'paid',
                'active',
                'expired',
            ])->default('pending');

            $table->string('payment_method')->nullable();

            $table->string('qris_image')->nullable();

            $table->date('start_date')->nullable();

            $table->date('end_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
