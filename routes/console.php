<?php

use App\Models\Subscription;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {

    Subscription::where('status', 'pending_cash')
        ->whereNotNull('cash_deadline')
        ->where('cash_deadline', '<', now())
        ->update([
            'status' => 'cancelled',
        ]);

})->hourly();
