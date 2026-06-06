<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kid_absences', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('kid_id')
                ->constrained('kids')
                ->cascadeOnDelete();

            $table->date('absence_date');

            $table->string('reason_type');

            $table->text('note')->nullable();

            $table->timestamps();

            $table->unique(['kid_id', 'absence_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kid_absences');
    }
};
