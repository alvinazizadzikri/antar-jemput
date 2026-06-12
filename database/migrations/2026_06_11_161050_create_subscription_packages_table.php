<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('price');
            $table->integer('duration_days');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('name');
        });

        DB::table('subscription_packages')->insert([
            [
                'name' => 'Harian',
                'price' => 50000,
                'duration_days' => 1,
                'description' => 'Berlaku untuk 1 hari sekolah.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mingguan',
                'price' => 250000,
                'duration_days' => 5,
                'description' => 'Berlaku untuk 5 hari sekolah.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bulanan',
                'price' => 800000,
                'duration_days' => 20,
                'description' => 'Berlaku untuk 20 hari sekolah.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_packages');
    }
};
