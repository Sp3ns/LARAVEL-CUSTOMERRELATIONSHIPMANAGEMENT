<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Customer;
use App\Models\FollowUp;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function customers(Request $request)
    {
        $query = Customer::with('assignedUser');
        if ($request->user()->isSales()) {
            $query->where('assigned_user_id', $request->user()->id);
        }
        $customers = $query->latest()->paginate(20);
        $statusCounts = Customer::query()
            ->when($request->user()->isSales(), fn($q) => $q->where('assigned_user_id', $request->user()->id))
            ->selectRaw('status, COUNT(*) as count')->groupBy('status')->pluck('count', 'status');
        return view('reports.customers', compact('customers', 'statusCounts'));
    }

    public function leads(Request $request)
    {
        $query = Lead::query();
        if ($request->user()->isSales()) {
            $query->where('assigned_user_id', $request->user()->id);
        }
        $statusCounts = (clone $query)->selectRaw('status, COUNT(*) as count')->groupBy('status')->pluck('count', 'status');
        $priorityCounts = (clone $query)->selectRaw('priority, COUNT(*) as count')->groupBy('priority')->pluck('count', 'priority');
        $leads = (clone $query)->with('assignedUser')->latest()->paginate(20);
        return view('reports.leads', compact('leads', 'statusCounts', 'priorityCounts'));
    }

    public function pipeline(Request $request)
    {
        $query = Lead::query();
        if ($request->user()->isSales()) {
            $query->where('assigned_user_id', $request->user()->id);
        }
        $pipeline = $query->selectRaw('status, COUNT(*) as count, COALESCE(SUM(expected_value),0) as total_value')
            ->groupBy('status')
            ->orderByRaw("FIELD(status,'New','Contacted','Qualified','Proposal Sent','Negotiation','Won','Lost')")
            ->get();
        return view('reports.pipeline', compact('pipeline'));
    }

    public function userActivity(Request $request)
    {
        $query = User::withCount('activities', 'customers', 'leads', 'followUps');
        if ($request->user()->isSales()) {
            $query->where('id', $request->user()->id);
        }
        $users = $query->orderBy('name')->get();
        return view('reports.user-activity', compact('users'));
    }

    public function followUps(Request $request)
    {
        $query = FollowUp::query();
        if ($request->user()->isSales()) {
            $query->where('user_id', $request->user()->id);
        }
        $statusCounts = (clone $query)->selectRaw('status, COUNT(*) as count')->groupBy('status')->pluck('count', 'status');
        $overdue = (clone $query)->where('status', '!=', 'Completed')->where('due_date', '<', now()->toDateString())->count();
        $followUps = (clone $query)->with(['user', 'customer', 'lead'])->orderBy('due_date')->paginate(20);
        return view('reports.follow-ups', compact('followUps', 'statusCounts', 'overdue'));
    }
}
