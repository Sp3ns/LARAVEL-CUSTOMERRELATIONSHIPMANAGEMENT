<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\FollowUp;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Follow-Up Controller
 */
class FollowUpController extends Controller
{
    public function index(Request $request)
    {
        $query = FollowUp::with(['user', 'customer', 'lead']);

        if ($request->user()->isSales()) {
            $query->where('user_id', $request->user()->id);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        $followUps = $query->orderBy('due_date')->paginate(15)->withQueryString();

        return view('follow-ups.index', compact('followUps'));
    }

    public function create(Request $request)
    {
        $users = User::orderBy('name')->get();
        $customers = Customer::orderBy('first_name')->get();
        $leads = Lead::orderBy('name')->get();

        $selectedCustomerId = $request->input('customer_id');
        $selectedLeadId = $request->input('lead_id');

        return view('follow-ups.create', compact(
            'users', 'customers', 'leads', 'selectedCustomerId', 'selectedLeadId'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'due_date'    => 'required|date',
            'status'      => 'required|in:' . implode(',', FollowUp::STATUSES),
            'customer_id' => 'nullable|exists:customers,id',
            'lead_id'     => 'nullable|exists:leads,id',
            'user_id'     => 'required|exists:users,id',
        ]);

        FollowUp::create($validated);

        return redirect()->route('follow-ups.index')
            ->with('success', 'Follow-up created successfully.');
    }

    public function show(FollowUp $followUp)
    {
        $followUp->load(['user', 'customer', 'lead']);
        return view('follow-ups.show', compact('followUp'));
    }

    public function edit(FollowUp $followUp)
    {
        if ($followUp->isCompleted()) {
            return redirect()->route('follow-ups.show', $followUp)
                ->with('error', 'Completed follow-ups cannot be edited. Reopen first.');
        }

        $users = User::orderBy('name')->get();
        $customers = Customer::orderBy('first_name')->get();
        $leads = Lead::orderBy('name')->get();

        return view('follow-ups.edit', compact('followUp', 'users', 'customers', 'leads'));
    }

    public function update(Request $request, FollowUp $followUp)
    {
        if ($followUp->isCompleted()) {
            return redirect()->route('follow-ups.show', $followUp)
                ->with('error', 'Completed follow-ups cannot be edited. Reopen first.');
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'due_date'    => 'required|date',
            'status'      => 'required|in:' . implode(',', FollowUp::STATUSES),
            'customer_id' => 'nullable|exists:customers,id',
            'lead_id'     => 'nullable|exists:leads,id',
            'user_id'     => 'required|exists:users,id',
        ]);

        $followUp->update($validated);

        return redirect()->route('follow-ups.show', $followUp)
            ->with('success', 'Follow-up updated successfully.');
    }

    public function destroy(FollowUp $followUp)
    {
        $followUp->delete();

        return redirect()->route('follow-ups.index')
            ->with('success', 'Follow-up deleted successfully.');
    }

    public function complete(FollowUp $followUp)
    {
        $followUp->update(['status' => 'Completed']);

        return redirect()->route('follow-ups.show', $followUp)
            ->with('success', 'Follow-up marked as completed.');
    }

    public function reopen(FollowUp $followUp)
    {
        $followUp->update(['status' => 'Pending']);

        return redirect()->route('follow-ups.show', $followUp)
            ->with('success', 'Follow-up reopened.');
    }
}
