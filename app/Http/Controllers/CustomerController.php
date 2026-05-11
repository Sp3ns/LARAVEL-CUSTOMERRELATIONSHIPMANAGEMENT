<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    private function authorizeAccess(Customer $customer): void
    {
        if (request()->user()->isSales() && $customer->assigned_user_id !== request()->user()->id) {
            abort(403, 'You can only access your own assigned customers.');
        }
    }

    public function index(Request $request)
    {
        $query = Customer::with('assignedUser');

        if ($request->user()->isSales()) {
            $query->where('assigned_user_id', $request->user()->id);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $customers = $query->latest()->paginate(15)->withQueryString();

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('customers.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:customers,email',
            'phone'      => 'required|string|max:50',
            'company'    => 'nullable|string|max:255',
            'address'    => 'nullable|string|max:1000',
            'status'     => 'required|in:active,inactive',
            'assigned_user_id' => 'nullable|exists:users,id',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        $this->authorizeAccess($customer);

        $customer->load([
            'assignedUser',
            'activities.user',
            'followUps.user',
        ]);

        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        $this->authorizeAccess($customer);

        $users = User::orderBy('name')->get();
        return view('customers.edit', compact('customer', 'users'));
    }

    public function update(Request $request, Customer $customer)
    {
        $this->authorizeAccess($customer);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:customers,email,' . $customer->id,
            'phone'      => 'required|string|max:50',
            'company'    => 'nullable|string|max:255',
            'address'    => 'nullable|string|max:1000',
            'status'     => 'required|in:active,inactive',
            'assigned_user_id' => 'nullable|exists:users,id',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.show', $customer)
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $this->authorizeAccess($customer);

        $relatedCount = $customer->activities()->count() + $customer->followUps()->count();
        if ($relatedCount > 0) {
            return redirect()->route('customers.show', $customer)
                ->with('error', "Cannot delete: {$relatedCount} related activities/follow-ups exist. Remove them first.");
        }

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
