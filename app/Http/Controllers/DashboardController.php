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

        Subscription::syncExpired();

        if ($user->role == 'parent') {
            $user->load(['kids', 'subscriptions']);

            $kids = $user->kids ?? collect();
            $subscriptions = $user->subscriptions ?? collect();

            $activeSubscriptionsCollection = $subscriptions->filter(function ($subscription) {
                return $subscription->isActiveToday();
            });

            $activeSubscriptions = $activeSubscriptionsCollection->count();

            $activeKidIds = $activeSubscriptionsCollection
                ->pluck('kid_id')
                ->filter()
                ->unique()
                ->values();

            $needSubscriptionCount = max($kids->count() - $activeKidIds->count(), 0);

            $latestKids = $kids
                ->sortByDesc('created_at')
                ->take(3)
                ->values();

            $latestSubscriptions = $subscriptions
                ->sortByDesc('created_at')
                ->take(3)
                ->values();

            return view('dashboard', compact(
                'kids',
                'subscriptions',
                'activeSubscriptions',
                'activeKidIds',
                'needSubscriptionCount',
                'latestKids',
                'latestSubscriptions'
            ));
        }

        if ($user->role == 'admin') {
            $totalKids = Kid::count();

            $totalDrivers = Driver::count();

            $totalSubscriptions = Subscription::where('status', 'active')
                ->whereDate('start_date', '<=', now()->toDateString())
                ->whereDate('end_date', '>=', now()->toDateString())
                ->count();

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
                'picked_up',
                'arrived_school',
                'picked_up_school',
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