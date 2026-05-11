<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Customer;
use App\Models\Lead;
use Illuminate\Http\Request;


class ActivityController extends Controller
{
   
    private function authorizeAccess(Activity $activity): void
    {
        if (request()->user()->isSales() && $activity->user_id !== request()->user()->id) {
            abort(403, 'You can only access your own activities.');
        }
    }

    public function index(Request $request)
    {
        $query = Activity::with(['user', 'customer', 'lead']);

        if ($request->user()->isSales()) {
            $query->where('user_id', $request->user()->id);
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        if ($search = $request->input('search')) {
            $query->where('description', 'like', "%{$search}%");
        }

        $activities = $query->latest()->paginate(20)->withQueryString();

        return view('activities.index', compact('activities'));
    }

    public function create(Request $request)
    {
        $customers = Customer::orderBy('first_name')->get();
        $leads = Lead::orderBy('name')->get();

        $selectedCustomerId = $request->input('customer_id');
        $selectedLeadId = $request->input('lead_id');

        return view('activities.create', compact(
            'customers', 'leads', 'selectedCustomerId', 'selectedLeadId'
        ));
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'        => 'required|in:' . implode(',', Activity::TYPES),
            'description' => 'required|string|max:5000',
            'customer_id' => 'nullable|exists:customers,id',
            'lead_id'     => 'nullable|exists:leads,id',
        ]);

        if (empty($validated['customer_id']) && empty($validated['lead_id'])) {
    return back()->withInput()->withErrors([
        'customer_id' => 'Please select either a customer or a lead.'
    ]); 
}

        $validated['user_id'] = $request->user()->id;

        Activity::create($validated);

        return redirect()->route('activities.index')
            ->with('success', 'Activity logged successfully.');
    }

    public function show(Activity $activity)
    {
        $this->authorizeAccess($activity);

        $activity->load(['user', 'customer', 'lead']);
        return view('activities.show', compact('activity'));
    }
   
    public function edit(Activity $activity)
    {
        $this->authorizeAccess($activity);

        $customers = Customer::orderBy('first_name')->get();
        $leads = Lead::orderBy('name')->get();
        return view('activities.edit', compact('activity', 'customers', 'leads'));
    }

    public function update(Request $request, Activity $activity)
    {
        $this->authorizeAccess($activity);

        $validated = $request->validate([
            'type'        => 'required|in:' . implode(',', Activity::TYPES),
            'description' => 'required|string|max:5000',
            'customer_id' => 'nullable|exists:customers,id',
            'lead_id'     => 'nullable|exists:leads,id',
        ]);

        if (!empty($validated['customer_id'])) {
            $validated['lead_id'] = null;
        }

        if (!empty($validated['lead_id'])) {
            $validated['customer_id'] = null;
        }

        $activity->update($validated);

        return redirect()->route('activities.show', $activity)
            ->with('success', 'Activity updated successfully.');
    }

    public function destroy(Activity $activity)
    {
        $this->authorizeAccess($activity);

        $activity->delete();

        return redirect()->route('activities.index')
            ->with('success', 'Activity deleted successfully.');
    }
}
