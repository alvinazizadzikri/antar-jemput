<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("
            ALTER TABLE riwayat_antar_jemputs
            MODIFY status ENUM(
                'assigned',
                'on_pickup',
                'picked',
                'on_delivery',
                'completed'
            ) DEFAULT 'assigned'
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE riwayat_antar_jemputs
            MODIFY status ENUM(
                'assigned',
                'on_pickup',
                'picked',
                'on_delivery',
                'delivered'
            ) DEFAULT 'assigned'
        ");
    }
};
