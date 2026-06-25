<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE subscriptions
            MODIFY status ENUM(
                'pending',
                'pending_cash',
                'active',
                'expired',
                'cancelled',
                'paid'
            ) NULL DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        DB::statement("
            UPDATE subscriptions
            SET status = 'pending'
            WHERE status IN ('expired', 'cancelled', 'paid')
        ");

        DB::statement("
            ALTER TABLE subscriptions
            MODIFY status ENUM(
                'pending',
                'pending_cash',
                'active'
            ) NULL DEFAULT 'pending'
        ");
    }
};