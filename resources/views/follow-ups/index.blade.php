@extends('layouts.app')
@section('title', 'Follow-Ups')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Follow-Up Management</h2>
            <p class="text-sm text-gray-500">Track tasks and due dates</p>
        </div>
        <a href="{{ route('follow-ups.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Follow-Up
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-100 p-4">
        <form method="GET" action="{{ route('follow-ups.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title..."
                       class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <select name="status" class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All Statuses</option>
                @foreach (\App\Models\FollowUp::STATUSES as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">Filter</button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('follow-ups.index') }}" class="px-4 py-2 text-gray-500 text-sm rounded-lg hover:bg-gray-50">Clear</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Linked To</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Assigned</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($followUps as $fu)
                        <tr class="hover:bg-gray-50 transition-colors {{ $fu->isOverdue() ? 'bg-red-50/50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('follow-ups.show', $fu) }}" class="text-sm font-medium text-gray-900 hover:text-indigo-600">{{ $fu->title }}</a>
                                @if($fu->isOverdue())
                                    <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-xs font-bold bg-red-100 text-red-700">OVERDUE</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm {{ $fu->isOverdue() ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                {{ $fu->due_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap"><x-badge :label="$fu->status" /></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                @if($fu->customer) <a href="{{ route('customers.show', $fu->customer) }}" class="text-indigo-500 hover:text-indigo-700">{{ $fu->customer->full_name }}</a>
                                @elseif($fu->lead) <a href="{{ route('leads.show', $fu->lead) }}" class="text-amber-500 hover:text-amber-700">{{ $fu->lead->name }}</a>
                                @else — @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $fu->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if(!$fu->isCompleted())
                                        <form method="POST" action="{{ route('follow-ups.complete', $fu) }}">@csrf @method('PATCH')
                                            <button class="text-emerald-500 hover:text-emerald-700" title="Mark Complete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('follow-ups.reopen', $fu) }}">@csrf @method('PATCH')
                                            <button class="text-amber-500 hover:text-amber-700" title="Reopen">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('follow-ups.edit', $fu) }}" class="text-gray-400 hover:text-indigo-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('follow-ups.destroy', $fu) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                                        <button class="text-gray-400 hover:text-red-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-sm text-gray-400">No follow-ups found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($followUps->hasPages())
            <div class="px-6 py-3 border-t border-gray-100">{{ $followUps->links() }}</div>
        @endif
    </div>
</div>
@endsection
