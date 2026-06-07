<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("
            ALTER TABLE subscriptions
            MODIFY status ENUM(
                'pending',
                'pending_cash',
                'paid',
                'active',
                'expired',
                'cancelled'
            ) DEFAULT 'pending'
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE subscriptions
            MODIFY status ENUM(
                'pending',
                'paid',
                'active',
                'expired'
            ) DEFAULT 'pending'
        ");
    }
};
