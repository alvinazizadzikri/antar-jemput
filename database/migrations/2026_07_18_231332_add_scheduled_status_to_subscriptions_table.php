<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {

            $table->enum('status', [
                'pending',
                'paid',
                'scheduled',
                'active',
                'expired',
                'cancelled'
            ])
            ->default('pending')
            ->change();

        });
    }


    public function down(): void
    {

        Schema::table('subscriptions', function (Blueprint $table) {

            $table->enum('status', [
                'pending',
                'paid',
                'active',
                'expired'
            ])
            ->default('pending')
            ->change();

        });

    }

};
