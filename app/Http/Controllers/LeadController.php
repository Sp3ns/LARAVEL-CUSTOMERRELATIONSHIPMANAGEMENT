<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Lead Controller
**/

class LeadController extends Controller
{
    private function authorizeAccess(Lead $lead): void
    {
        if (request()->user()->isSales() && $lead->assigned_user_id !== request()->user()->id) {
            abort(403, 'You can only access your own assigned leads.');
        }
    }

    public function index(Request $request)
    {
        $query = Lead::with('assignedUser');

        if ($request->user()->isSales()) {
            $query->where('assigned_user_id', $request->user()->id);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('source', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($priority = $request->input('priority')) {
            $query->where('priority', $priority);
        }

        $leads = $query->latest()->paginate(15)->withQueryString();

        return view('leads.index', compact('leads'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('leads.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'nullable|email|max:255',
            'phone'    => 'nullable|string|max:50',
            'source'   => 'nullable|string|max:255',
            'status'   => 'required|in:' . implode(',', Lead::STATUSES),
            'priority' => 'required|in:' . implode(',', Lead::PRIORITIES),
            'expected_value'    => 'nullable|numeric|min:0',
            'notes'             => 'nullable|string|max:5000',
            'assigned_user_id'  => 'nullable|exists:users,id',
        ]);

        Lead::create($validated);

        return redirect()->route('leads.index')
            ->with('success', 'Lead created successfully.');
    }

    public function show(Lead $lead)
    {
        $this->authorizeAccess($lead);

        $lead->load([
            'assignedUser',
            'activities.user',
            'followUps.user',
        ]);

        return view('leads.show', compact('lead'));
    }
    
    public function edit(Lead $lead)
    {
        $this->authorizeAccess($lead);

        $users = User::orderBy('name')->get();
        return view('leads.edit', compact('lead', 'users'));
    }

    public function update(Request $request, Lead $lead)
    {
        $this->authorizeAccess($lead);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'nullable|email|max:255',
            'phone'    => 'nullable|string|max:50',
            'source'   => 'nullable|string|max:255',
            'status'   => 'required|in:' . implode(',', Lead::STATUSES),
            'priority' => 'required|in:' . implode(',', Lead::PRIORITIES),
            'expected_value'    => 'nullable|numeric|min:0',
            'notes'             => 'nullable|string|max:5000',
            'assigned_user_id'  => 'nullable|exists:users,id',
        ]);

        $lead->update($validated);

        return redirect()->route('leads.show', $lead)
            ->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead)
    {
        $this->authorizeAccess($lead);

        $relatedCount = $lead->activities()->count() + $lead->followUps()->count();
        if ($relatedCount > 0) {
            return redirect()->route('leads.show', $lead)
                ->with('error', "Cannot delete: {$relatedCount} related activities/follow-ups exist. Remove them first.");
        }

        $lead->delete();

        return redirect()->route('leads.index')
            ->with('success', 'Lead deleted successfully.');
    }

    public function convert(Lead $lead)
    {
        if ($lead->status === 'Won') {
            return redirect()->route('leads.show', $lead)
                ->with('error', 'This lead has already been converted.');
        }

        if (empty($lead->email)) {
            return redirect()->route('leads.show', $lead)
                ->with('error', 'Lead must have an email address before converting to a customer.');
        }

        if (Customer::where('email', $lead->email)->exists()) {
            return redirect()->route('leads.show', $lead)
                ->with('error', 'A customer with this email already exists.');
        }

        $nameParts = explode(' ', $lead->name, 2);

        $customer = Customer::create([
            'first_name'       => $nameParts[0],
            'last_name'        => $nameParts[1] ?? '',
            'email'            => $lead->email,
            'phone'            => $lead->phone ?? 'N/A',
            'company'          => null,
            'status'           => 'active',
            'assigned_user_id' => $lead->assigned_user_id,
        ]);

        $lead->update(['status' => 'Won']);

        return redirect()->route('customers.show', $customer)
            ->with('success', "Lead \"{$lead->name}\" converted to customer successfully.");
    }
}
