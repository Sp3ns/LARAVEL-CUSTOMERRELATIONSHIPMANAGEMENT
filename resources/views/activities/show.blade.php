@extends('layouts.app')
@section('title', 'Activity Detail')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('activities.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Back to Activities
        </a>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <x-badge :label="$activity->type" />
                <span class="text-sm text-gray-400">{{ $activity->created_at->format('M d, Y h:i A') }}</span>
            </div>
            <a href="{{ route('activities.edit', $activity) }}" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">Edit</a>
        </div>
        <p class="text-gray-800 leading-relaxed">{{ $activity->description }}</p>
        <div class="mt-6 pt-4 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div><p class="text-xs text-gray-400">Logged by</p><p class="text-sm font-medium text-gray-700">{{ $activity->user->name }}</p></div>
            <div><p class="text-xs text-gray-400">Customer</p>
                @if($activity->customer)
                    <a href="{{ route('customers.show', $activity->customer) }}" class="text-sm text-indigo-600 hover:text-indigo-800">{{ $activity->customer->full_name }}</a>
                @else <p class="text-sm text-gray-400">—</p> @endif
            </div>
            <div><p class="text-xs text-gray-400">Lead</p>
                @if($activity->lead)
                    <a href="{{ route('leads.show', $activity->lead) }}" class="text-sm text-amber-600 hover:text-amber-800">{{ $activity->lead->name }}</a>
                @else <p class="text-sm text-gray-400">—</p> @endif
            </div>
        </div>
    </div>
</div>
@endsection
