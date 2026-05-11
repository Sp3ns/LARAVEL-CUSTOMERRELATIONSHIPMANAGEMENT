@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- ── Stat Cards ──────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-stat-card title="Total Customers" :value="$totalCustomers" color="indigo"
            icon="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />

        <x-stat-card title="Active Leads" :value="$activeLeads" color="amber"
            icon="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />

        <x-stat-card title="Completed Follow-Ups" :value="$completedFollowUps" color="emerald"
            icon="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />

        <x-stat-card title="Pending Follow-Ups" :value="$pendingFollowUps" color="rose"
            icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
    </div>

    {{-- ── Charts Row ──────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Leads by Status Pie --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Leads by Status</h3>
            <div class="h-64">
                <canvas id="leadsChart"></canvas>
            </div>
        </div>

        {{-- Follow-ups Completed by Month --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Follow-Ups Completed (Last 6 Months)</h3>
            <div class="h-64">
                <canvas id="followUpsChart"></canvas>
            </div>
        </div>
    </div>

    {{-- ── Recent Activity & Upcoming Follow-Ups ───────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Activities --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-700">Recent Activities</h3>
                <a href="{{ route('activities.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">View All →</a>
            </div>
            <div class="space-y-3">
                @forelse ($recentActivities as $activity)
                    <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0
                            {{ $activity->type === 'call' ? 'bg-sky-100 text-sky-600' : '' }}
                            {{ $activity->type === 'email' ? 'bg-violet-100 text-violet-600' : '' }}
                            {{ $activity->type === 'meeting' ? 'bg-amber-100 text-amber-600' : '' }}
                            {{ $activity->type === 'note' ? 'bg-gray-100 text-gray-600' : '' }}">
                            @if($activity->type === 'call')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            @elseif($activity->type === 'email')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            @elseif($activity->type === 'meeting')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-800 truncate">{{ Str::limit($activity->description, 60) }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $activity->user->name }} · {{ $activity->created_at->diffForHumans() }}
                                @if($activity->customer)
                                    · <span class="text-indigo-500">{{ $activity->customer->full_name }}</span>
                                @elseif($activity->lead)
                                    · <span class="text-amber-500">{{ $activity->lead->name }}</span>
                                @endif
                            </p>
                        </div>
                        <x-badge :label="$activity->type" />
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-6">No recent activities</p>
                @endforelse
            </div>
        </div>

        {{-- Upcoming Follow-Ups --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-700">Upcoming Follow-Ups</h3>
                <a href="{{ route('follow-ups.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">View All →</a>
            </div>
            <div class="space-y-3">
                @forelse ($upcomingFollowUps as $followUp)
                    <a href="{{ route('follow-ups.show', $followUp) }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0
                            {{ $followUp->isOverdue() ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $followUp->title }}</p>
                            <p class="text-xs text-gray-400">Due: {{ $followUp->due_date->format('M d, Y') }} · {{ $followUp->user->name }}</p>
                        </div>
                        <x-badge :label="$followUp->status" />
                    </a>
                @empty
                    <p class="text-sm text-gray-400 text-center py-6">No upcoming follow-ups</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ── Charts Script ───────────────────────────────────────────── --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lead status colors
    const statusColors = {
        'New': '#3B82F6', 'Contacted': '#F59E0B', 'Qualified': '#8B5CF6',
        'Proposal Sent': '#6366F1', 'Negotiation': '#F97316', 'Won': '#10B981', 'Lost': '#EF4444'
    };

    const leadsData = @json($leadsByStatus);
    if (Object.keys(leadsData).length > 0) {
        new Chart(document.getElementById('leadsChart'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(leadsData),
                datasets: [{
                    data: Object.values(leadsData),
                    backgroundColor: Object.keys(leadsData).map(s => statusColors[s] || '#9CA3AF'),
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { padding: 16, usePointStyle: true, pointStyleWidth: 10 } } },
                cutout: '65%'
            }
        });
    }

    const monthsData = @json($followUpsByMonth);
    const monthNames = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    new Chart(document.getElementById('followUpsChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(monthsData).map(m => monthNames[m] || m),
            datasets: [{
                label: 'Completed',
                data: Object.values(monthsData),
                backgroundColor: '#6366F1',
                borderRadius: 6,
                barThickness: 28
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#F3F4F6' } },
                x: { grid: { display: false } }
            },
            plugins: { legend: { display: false } }
        }
    });
});
</script>
@endsection
