<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Kid;
use App\Models\RiwayatAntarJemput;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role == 'parent') {
            $kids = $user->kids ?? collect();
            $subscriptions = $user->subscriptions ?? collect();
            $activeSubscriptions = $subscriptions->where('status', 'active')->count();

            return view('dashboard', compact(
                'kids',
                'subscriptions',
                'activeSubscriptions'
            ));
        }

        if ($user->role == 'admin') {
            $totalKids = Kid::count();
            $totalDrivers = Driver::count();
            $totalSubscriptions = Subscription::where('status', 'active')->count();
            $totalTrips = RiwayatAntarJemput::count();

            return view('dashboard_admin', compact(
                'totalKids',
                'totalDrivers',
                'totalSubscriptions',
                'totalTrips'
            ));
        }

        if ($user->role == 'driver') {
            $driver = Driver::where('user_id', $user->id)->first();

            $jobs = collect();

            if ($driver) {
                $jobs = RiwayatAntarJemput::with('kid')
                    ->where('driver_id', $driver->id)
                    ->latest()
                    ->get();
            }

            $totalJobs = $jobs->count();
            $activeJobs = $jobs->whereIn('status', [
                'assigned',
                'on_pickup',
                'picked',
                'on_delivery',
            ])->count();

            return view('dashboard_driver', compact(
                'jobs',
                'totalJobs',
                'activeJobs'
            ));
        }

        abort(403);
    }
}
