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
        Schema::table('subscriptions', function (Blueprint $table) {

            $table->boolean('is_paused')
                ->default(false);

            $table->date('pause_start')
                ->nullable();

            $table->date('pause_end')
                ->nullable();

            $table->integer('remaining_days')
                ->default(0);

            $table->text('pause_reason')
                ->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            //
        });
    }
};
