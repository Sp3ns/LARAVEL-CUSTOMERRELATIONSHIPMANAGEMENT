@extends('layouts.app')
@section('title', $customer->full_name)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('customers.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Customers
        </a>
        <div class="flex gap-2">
            <a href="{{ route('customers.edit', $customer) }}" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">Edit</a>
            <a href="{{ route('activities.create', ['customer_id' => $customer->id]) }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">+ Activity</a>
            <a href="{{ route('follow-ups.create', ['customer_id' => $customer->id]) }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">+ Follow-Up</a>
        </div>
    </div>

    {{-- Customer Info Card --}}
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl font-bold">
                {{ strtoupper(substr($customer->first_name, 0, 1) . substr($customer->last_name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-bold text-gray-900">{{ $customer->full_name }}</h2>
                <p class="text-sm text-gray-500">{{ $customer->company ?? 'No company' }}</p>
                <div class="flex items-center gap-3 mt-2">
                    <x-badge :label="$customer->status" />
                    @if($customer->assignedUser)
                        <span class="text-xs text-gray-400">Assigned to: <span class="font-medium text-gray-600">{{ $customer->assignedUser->name }}</span></span>
                    @endif
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6 pt-4 border-t border-gray-100">
            <div><p class="text-xs text-gray-400">Email</p><p class="text-sm text-gray-800">{{ $customer->email }}</p></div>
            <div><p class="text-xs text-gray-400">Phone</p><p class="text-sm text-gray-800">{{ $customer->phone }}</p></div>
            <div><p class="text-xs text-gray-400">Address</p><p class="text-sm text-gray-800">{{ $customer->address ?? '—' }}</p></div>
        </div>
    </div>

    {{-- Activities & Follow-Ups --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Activities --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Activities ({{ $customer->activities->count() }})</h3>
            <div class="space-y-3">
                @forelse ($customer->activities->sortByDesc('created_at') as $activity)
                    <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50">
                        <x-badge :label="$activity->type" />
                        <div class="flex-1">
                            <p class="text-sm text-gray-700">{{ Str::limit($activity->description, 100) }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $activity->user->name }} · {{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-4">No activities yet</p>
                @endforelse
            </div>
        </div>

        {{-- Follow-Ups --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Follow-Ups ({{ $customer->followUps->count() }})</h3>
            <div class="space-y-3">
                @forelse ($customer->followUps->sortBy('due_date') as $followUp)
                    <a href="{{ route('follow-ups.show', $followUp) }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $followUp->title }}</p>
                            <p class="text-xs text-gray-400">Due: {{ $followUp->due_date->format('M d, Y') }} · {{ $followUp->user->name }}</p>
                        </div>
                        <x-badge :label="$followUp->status" />
                    </a>
                @empty
                    <p class="text-sm text-gray-400 text-center py-4">No follow-ups yet</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
