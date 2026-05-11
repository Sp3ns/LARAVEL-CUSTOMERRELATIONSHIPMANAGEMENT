@extends('layouts.app')
@section('title', 'Follow-Up Completion Report')

@section('content')
<div class="space-y-6">
    <div>
        <a href="{{ route('reports.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 flex items-center gap-1 mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Reports
        </a>
        <h2 class="text-xl font-bold text-gray-900">Follow-Up Completion Report</h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        @foreach (\App\Models\FollowUp::STATUSES as $s)
            <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
                <p class="text-2xl font-bold text-gray-900">{{ $statusCounts[$s] ?? 0 }}</p>
                <p class="text-sm text-gray-500">{{ $s }}</p>
            </div>
        @endforeach
        <div class="bg-red-50 rounded-xl border border-red-100 p-4 text-center">
            <p class="text-2xl font-bold text-red-600">{{ $overdue }}</p>
            <p class="text-sm text-red-500">Overdue</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Due Date</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Assigned</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Linked To</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($followUps as $fu)
                    <tr class="{{ $fu->isOverdue() ? 'bg-red-50/50' : '' }}">
                        <td class="px-6 py-3 text-sm text-gray-800">{{ $fu->title }}</td>
                        <td class="px-6 py-3 text-sm {{ $fu->isOverdue() ? 'text-red-600 font-semibold' : 'text-gray-600' }}">{{ $fu->due_date->format('M d, Y') }}</td>
                        <td class="px-6 py-3"><x-badge :label="$fu->status" /></td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ $fu->user->name }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">
                            @if($fu->customer) {{ $fu->customer->full_name }}
                            @elseif($fu->lead) {{ $fu->lead->name }}
                            @else — @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if($followUps->hasPages()) <div class="px-6 py-3 border-t">{{ $followUps->links() }}</div> @endif
    </div>
</div>
@endsection
