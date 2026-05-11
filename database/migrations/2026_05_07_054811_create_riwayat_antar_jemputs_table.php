<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('riwayat_antar_jemputs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('driver_id')->constrained()->cascadeOnDelete();

            $table->foreignId('kid_id')->constrained()->cascadeOnDelete();

            $table->enum('status', [
                'assigned',
                'on_pickup',
                'picked',
                'on_delivery',
                'delivered',
            ])->default('assigned');

            $table->timestamp('pickup_time')->nullable();
            $table->timestamp('dropoff_time')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_antar_jemputs');
    }
};
