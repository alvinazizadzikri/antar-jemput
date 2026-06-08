<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Tahap 1: Tambahkan status baru tanpa menghapus status lama dulu
        |--------------------------------------------------------------------------
        */
        DB::statement("
            ALTER TABLE riwayat_antar_jemputs 
            MODIFY status ENUM(
                'assigned',
                'on_pickup',
                'picked',
                'on_delivery',
                'to_home_pickup',
                'picked_up_home',
                'to_school',
                'arrived_school',
                'waiting_return',
                'to_school_pickup',
                'picked_up_school',
                'to_home',
                'picked_up',
                'completed'
            ) NOT NULL DEFAULT 'assigned'
        ");

        /*
        |--------------------------------------------------------------------------
        | Tahap 2: Konversi status lama / status terlalu rinci ke status inti
        |--------------------------------------------------------------------------
        */
        DB::statement("
            UPDATE riwayat_antar_jemputs
            SET status = 'assigned'
            WHERE status IN ('on_pickup', 'to_home_pickup')
        ");

        DB::statement("
            UPDATE riwayat_antar_jemputs
            SET status = 'picked_up'
            WHERE status IN ('picked', 'picked_up_home')
        ");

        DB::statement("
            UPDATE riwayat_antar_jemputs
            SET status = 'arrived_school'
            WHERE status IN ('on_delivery', 'to_school', 'arrived_school', 'waiting_return')
        ");

        DB::statement("
            UPDATE riwayat_antar_jemputs
            SET status = 'picked_up_school'
            WHERE status IN ('to_school_pickup', 'picked_up_school')
        ");

        DB::statement("
            UPDATE riwayat_antar_jemputs
            SET status = 'completed'
            WHERE status IN ('to_home', 'completed')
        ");

        /*
        |--------------------------------------------------------------------------
        | Tahap 3: Rapikan enum hanya menjadi status inti
        |--------------------------------------------------------------------------
        */
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

    public function down(): void
    {
        DB::statement("
            ALTER TABLE riwayat_antar_jemputs 
            MODIFY status ENUM(
                'assigned',
                'on_pickup',
                'picked',
                'on_delivery',
                'completed'
            ) NOT NULL DEFAULT 'assigned'
        ");

        DB::statement("
            UPDATE riwayat_antar_jemputs
            SET status = 'picked'
            WHERE status = 'picked_up'
        ");

        DB::statement("
            UPDATE riwayat_antar_jemputs
            SET status = 'on_delivery'
            WHERE status IN ('arrived_school', 'picked_up_school')
        ");
    }
};
