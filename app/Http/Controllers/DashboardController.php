<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Customer;
use App\Models\FollowUp;
use App\Models\Lead;
use Illuminate\Http\Request;

/**
 * Dashboard Controller
 */
class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $customerQuery = Customer::query();
        $leadQuery = Lead::query();
        $followUpQuery = FollowUp::query();
        $activityQuery = Activity::query();
        if ($user->isSales()) {
            $customerQuery->where('assigned_user_id', $user->id);
            $leadQuery->where('assigned_user_id', $user->id);
            $followUpQuery->where('user_id', $user->id);
            $activityQuery->where('user_id', $user->id);
        }

        $totalCustomers = (clone $customerQuery)->count();
        $activeLeads = (clone $leadQuery)->whereNotIn('status', ['Won', 'Lost'])->count();
        $completedFollowUps = (clone $followUpQuery)->where('status', 'Completed')->count();
        $pendingFollowUps = (clone $followUpQuery)->where('status', '!=', 'Completed')->count();
        $recentActivities = (clone $activityQuery)
            ->with(['user', 'customer', 'lead'])
            ->latest()
            ->take(10)
            ->get();

        $upcomingFollowUps = (clone $followUpQuery)
            ->where('status', '!=', 'Completed')
            ->where('due_date', '>=', now()->toDateString())
            ->where('due_date', '<=', now()->addDays(7)->toDateString())
            ->with(['user', 'customer', 'lead'])
            ->orderBy('due_date')
            ->take(10)
            ->get();

        $leadsByStatus = Lead::query()
            ->when($user->isSales(), fn ($q) => $q->where('assigned_user_id', $user->id))
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $followUpsByMonth = FollowUp::query()
            ->when($user->isSales(), fn ($q) => $q->where('user_id', $user->id))
            ->where('status', 'Completed')
            ->where('updated_at', '>=', now()->subMonths(6))
            ->selectRaw('MONTH(updated_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        return view('dashboard', compact(
            'totalCustomers',
            'activeLeads',
            'completedFollowUps',
            'pendingFollowUps',
            'recentActivities',
            'upcomingFollowUps',
            'leadsByStatus',
            'followUpsByMonth',
        ));
    }
}
