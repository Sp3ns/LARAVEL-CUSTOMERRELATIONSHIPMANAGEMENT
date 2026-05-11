@extends('layouts.app')
@section('title', 'Leads')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Lead Management</h2>
            <p class="text-sm text-gray-500">Track and convert your sales leads</p>
        </div>
        <a href="{{ route('leads.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Lead
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-100 p-4">
        <form method="GET" action="{{ route('leads.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or source..."
                       class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <select name="status" class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All Statuses</option>
                @foreach (\App\Models\Lead::STATUSES as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
            <select name="priority" class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All Priorities</option>
                @foreach (\App\Models\Lead::PRIORITIES as $p)
                    <option value="{{ $p }}" {{ request('priority') === $p ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">Filter</button>
            @if(request()->hasAny(['search', 'status', 'priority']))
                <a href="{{ route('leads.index') }}" class="px-4 py-2 text-gray-500 text-sm rounded-lg hover:bg-gray-50">Clear</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Source</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Value</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Assigned</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($leads as $lead)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('leads.show', $lead) }}" class="text-sm font-medium text-gray-900 hover:text-indigo-600">{{ $lead->name }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm text-gray-600">{{ $lead->email ?? '—' }}</p>
                                <p class="text-xs text-gray-400">{{ $lead->phone ?? '' }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $lead->source ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap"><x-badge :label="$lead->status" /></td>
                            <td class="px-6 py-4 whitespace-nowrap"><x-badge :label="$lead->priority" /></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $lead->expected_value ? '$' . number_format($lead->expected_value, 2) : '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $lead->assignedUser?->name ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if(!in_array($lead->status, ['Won', 'Lost']))
                                        <form method="POST" action="{{ route('leads.convert', $lead) }}" onsubmit="return confirm('Convert this lead to a customer?')">
                                            @csrf
                                            <button type="submit" class="text-emerald-500 hover:text-emerald-700 transition-colors" title="Convert to Customer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('leads.edit', $lead) }}" class="text-gray-400 hover:text-indigo-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('leads.destroy', $lead) }}" onsubmit="return confirm('Delete this lead?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-6 py-12 text-center text-sm text-gray-400">No leads found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($leads->hasPages())
            <div class="px-6 py-3 border-t border-gray-100">{{ $leads->links() }}</div>
        @endif
    </div>
</div>
@endsection
