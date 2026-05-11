@extends('layouts.app')
@section('title', 'Customers')

@section('content')
<div class="space-y-4">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Customer Management</h2>
            <p class="text-sm text-gray-500">Manage your customer relationships</p>
        </div>
        <a href="{{ route('customers.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Customer
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-100 p-4">
        <form method="GET" action="{{ route('customers.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or company..."
                       class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <select name="status" class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">Filter</button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('customers.index') }}" class="px-4 py-2 text-gray-500 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">Clear</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Assigned To</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($customers as $customer)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('customers.show', $customer) }}" class="text-sm font-medium text-gray-900 hover:text-indigo-600">
                                    {{ $customer->full_name }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm text-gray-600">{{ $customer->email }}</p>
                                <p class="text-xs text-gray-400">{{ $customer->phone }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $customer->company ?? 'No Company' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $customer->assignedUser?->name ?? 'Unassigned' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap"><x-badge :label="$customer->status" /></td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('customers.edit', $customer) }}" class="text-gray-400 hover:text-indigo-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('customers.destroy', $customer) }}" onsubmit="return confirm('Delete this customer?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-sm text-gray-400">No customers found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($customers->hasPages())
            <div class="px-6 py-3 border-t border-gray-100">{{ $customers->links() }}</div>
        @endif
    </div>
</div>
@endsection
