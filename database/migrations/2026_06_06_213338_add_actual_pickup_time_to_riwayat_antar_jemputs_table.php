<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('riwayat_antar_jemputs', 'actual_pickup_time')) {
            Schema::table('riwayat_antar_jemputs', function (Blueprint $table) {
                $table->timestamp('actual_pickup_time')
                    ->nullable()
                    ->after('pickup_time');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('riwayat_antar_jemputs', 'actual_pickup_time')) {
            Schema::table('riwayat_antar_jemputs', function (Blueprint $table) {
                $table->dropColumn('actual_pickup_time');
            });
        }
    }
};
