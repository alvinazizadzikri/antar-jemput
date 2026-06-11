<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('kid_absences', 'absence_type')) {
            Schema::table('kid_absences', function (Blueprint $table) {
                $table->string('absence_type')
                    ->default('full_day')
                    ->after('kid_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('kid_absences', 'absence_type')) {
            Schema::table('kid_absences', function (Blueprint $table) {
                $table->dropColumn('absence_type');
            });
        }
    }
};
