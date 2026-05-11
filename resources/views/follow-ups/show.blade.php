@extends('layouts.app')
@section('title', $followUp->title)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('follow-ups.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Back
        </a>
        <div class="flex gap-2">
            @if(!$followUp->isCompleted())
                <form method="POST" action="{{ route('follow-ups.complete', $followUp) }}">@csrf @method('PATCH')
                    <button class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700">Mark Complete</button>
                </form>
                <a href="{{ route('follow-ups.edit', $followUp) }}" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">Edit</a>
            @else
                <form method="POST" action="{{ route('follow-ups.reopen', $followUp) }}">@csrf @method('PATCH')
                    <button class="px-4 py-2 bg-amber-500 text-white text-sm font-medium rounded-lg hover:bg-amber-600">Reopen</button>
                </form>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <div class="flex items-center gap-3 mb-4">
            <h2 class="text-xl font-bold text-gray-900">{{ $followUp->title }}</h2>
            <x-badge :label="$followUp->status" />
            @if($followUp->isOverdue())
                <span class="px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-700">OVERDUE</span>
            @endif
        </div>
        @if($followUp->description)
            <p class="text-gray-700 mb-6">{{ $followUp->description }}</p>
        @endif
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 border-t border-gray-100">
            <div>
                <p class="text-xs text-gray-400">Due Date</p>
                <p class="text-sm font-medium {{ $followUp->isOverdue() ? 'text-red-600' : 'text-gray-800' }}">{{ $followUp->due_date->format('F d, Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">Assigned To</p>
                <p class="text-sm font-medium text-gray-800">{{ $followUp->user->name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">Linked To</p>
                @if($followUp->customer)
                    <a href="{{ route('customers.show', $followUp->customer) }}" class="text-sm text-indigo-600 hover:text-indigo-800">{{ $followUp->customer->full_name }}</a>
                @elseif($followUp->lead)
                    <a href="{{ route('leads.show', $followUp->lead) }}" class="text-sm text-amber-600 hover:text-amber-800">{{ $followUp->lead->name }}</a>
                @else
                    <p class="text-sm text-gray-400">—</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
