@extends('layouts.app')
@section('title', 'Activities')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Activity Log</h2>
            <p class="text-sm text-gray-500">Track all calls, emails, meetings, and notes</p>
        </div>
        <a href="{{ route('activities.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Log Activity
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-100 p-4">
        <form method="GET" action="{{ route('activities.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search in descriptions..."
                       class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <select name="type" class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All Types</option>
                @foreach (\App\Models\Activity::TYPES as $t)
                    <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">Filter</button>
            @if(request()->hasAny(['search', 'type']))
                <a href="{{ route('activities.index') }}" class="px-4 py-2 text-gray-500 text-sm rounded-lg hover:bg-gray-50">Clear</a>
            @endif
        </form>
    </div>

    {{-- Timeline --}}
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <div class="space-y-1">
            @forelse ($activities as $activity)
                <div class="flex gap-4 p-4 rounded-lg hover:bg-gray-50 transition-colors border-l-4
                    {{ $activity->type === 'call' ? 'border-sky-400' : '' }}
                    {{ $activity->type === 'email' ? 'border-violet-400' : '' }}
                    {{ $activity->type === 'meeting' ? 'border-amber-400' : '' }}
                    {{ $activity->type === 'note' ? 'border-gray-300' : '' }}">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <x-badge :label="$activity->type" />
                            <span class="text-xs text-gray-400">{{ $activity->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                        <p class="text-sm text-gray-800">{{ $activity->description }}</p>
                        <div class="flex items-center gap-3 mt-2 text-xs text-gray-400">
                            <span>By: <span class="font-medium text-gray-600">{{ $activity->user->name }}</span></span>
                            @if($activity->customer)
                                <a href="{{ route('customers.show', $activity->customer) }}" class="text-indigo-500 hover:text-indigo-700">Customer: {{ $activity->customer->full_name }}</a>
                            @endif
                            @if($activity->lead)
                                <a href="{{ route('leads.show', $activity->lead) }}" class="text-amber-500 hover:text-amber-700">Lead: {{ $activity->lead->name }}</a>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-start gap-1">
                        <a href="{{ route('activities.edit', $activity) }}" class="text-gray-400 hover:text-indigo-600 p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                        <form method="POST" action="{{ route('activities.destroy', $activity) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                            <button class="text-gray-400 hover:text-red-600 p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400 text-center py-12">No activities found.</p>
            @endforelse
        </div>
        @if ($activities->hasPages())
            <div class="mt-4 pt-4 border-t border-gray-100">{{ $activities->links() }}</div>
        @endif
    </div>
</div>
@endsection
