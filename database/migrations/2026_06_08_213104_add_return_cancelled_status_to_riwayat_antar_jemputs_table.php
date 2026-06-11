<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE riwayat_antar_jemputs
            MODIFY status ENUM(
                'assigned',
                'picked_up',
                'arrived_school',
                'picked_up_school',
                'return_cancelled',
                'completed'
            ) NOT NULL DEFAULT 'assigned'
        ");
    }

    public function down(): void
    {
        DB::statement("
            UPDATE riwayat_antar_jemputs
            SET status = 'arrived_school'
            WHERE status = 'return_cancelled'
        ");

        DB::statement("
            ALTER TABLE riwayat_antar_jemputs
            MODIFY status ENUM(
                'assigned',
                'picked_up',
                'arrived_school',
                'picked_up_school',
                'completed'
            ) NOT NULL DEFAULT 'assigned'
        ");
    }
};
