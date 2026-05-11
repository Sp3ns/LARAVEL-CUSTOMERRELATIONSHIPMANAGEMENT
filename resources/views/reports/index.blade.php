@extends('layouts.app')
@section('title', 'Reports')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Reports</h2>
        <p class="text-sm text-gray-500">Generate and view CRM reports</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @php
            $reports = [
                ['route' => 'reports.customers', 'title' => 'Customer Report', 'desc' => 'Customer list with status distribution', 'color' => 'indigo', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['route' => 'reports.leads', 'title' => 'Lead Status Report', 'desc' => 'Lead distribution by status and priority', 'color' => 'amber', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'],
                ['route' => 'reports.pipeline', 'title' => 'Sales Pipeline', 'desc' => 'Expected value by pipeline stage', 'color' => 'emerald', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ['route' => 'reports.user-activity', 'title' => 'User Activity', 'desc' => 'Activity counts per team member', 'color' => 'violet', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                ['route' => 'reports.follow-ups', 'title' => 'Follow-Up Completion', 'desc' => 'Follow-up status and overdue tracking', 'color' => 'rose', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ];
            $colors = ['indigo' => 'bg-indigo-50 text-indigo-600 border-indigo-100', 'amber' => 'bg-amber-50 text-amber-600 border-amber-100', 'emerald' => 'bg-emerald-50 text-emerald-600 border-emerald-100', 'violet' => 'bg-violet-50 text-violet-600 border-violet-100', 'rose' => 'bg-rose-50 text-rose-600 border-rose-100'];
        @endphp

        @foreach ($reports as $r)
            <a href="{{ route($r['route']) }}" class="bg-white rounded-xl border border-gray-100 p-6 hover:shadow-lg hover:border-gray-200 transition-all duration-300 group">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl {{ $colors[$r['color']] }} flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $r['icon'] }}"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $r['title'] }}</h3>
                        <p class="text-xs text-gray-500 mt-1">{{ $r['desc'] }}</p>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection
