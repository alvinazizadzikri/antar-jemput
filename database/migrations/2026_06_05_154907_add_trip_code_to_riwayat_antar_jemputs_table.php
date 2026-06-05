<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('riwayat_antar_jemputs', 'trip_code')) {

            Schema::table('riwayat_antar_jemputs', function (Blueprint $table) {

                $table->string('trip_code')
                    ->nullable()
                    ->after('driver_id');

            });

        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('riwayat_antar_jemputs', 'trip_code')) {

            Schema::table('riwayat_antar_jemputs', function (Blueprint $table) {

                $table->dropColumn('trip_code');

            });

        }
    }
};
