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
        Schema::table('subscriptions', function (Blueprint $table) {

            $table->date('cash_due_date')
                ->nullable()
                ->after('end_date');

            $table->timestamp('cash_paid_at')
                ->nullable()
                ->after('cash_due_date');

            $table->foreignId('verified_by')
                ->nullable()
                ->after('cash_paid_at')
                ->constrained('users')
                ->nullOnDelete();

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
