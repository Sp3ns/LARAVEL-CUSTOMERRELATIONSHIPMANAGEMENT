@extends('layouts.app')
@section('title', 'Lead Status Report')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('reports.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 flex items-center gap-1 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Reports
            </a>
            <h2 class="text-xl font-bold text-gray-900">Lead Status Report</h2>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('reports.leads.export-csv') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition" title="Export CSV">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> CSV
            </a>
            <a href="{{ route('reports.leads.export-pdf') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition" title="Export PDF">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> PDF
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- By Status --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">By Status</h3>
            <div class="space-y-2">
                @foreach (\App\Models\Lead::STATUSES as $s)
                    @php $count = $statusCounts[$s] ?? 0; $total = $statusCounts->sum(); $pct = $total > 0 ? round($count / $total * 100) : 0; @endphp
                    <div class="flex items-center gap-3">
                        <div class="w-24"><x-badge :label="$s" /></div>
                        <div class="flex-1 bg-gray-100 rounded-full h-2"><div class="bg-indigo-500 h-2 rounded-full" style="width: {{ $pct }}%"></div></div>
                        <span class="text-sm text-gray-600 w-12 text-right">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- By Priority --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">By Priority</h3>
            <div class="space-y-2">
                @foreach (\App\Models\Lead::PRIORITIES as $p)
                    @php $count = $priorityCounts[$p] ?? 0; @endphp
                    <div class="flex items-center gap-3">
                        <div class="w-24"><x-badge :label="$p" /></div>
                        <span class="text-2xl font-bold text-gray-900">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Priority</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Value</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Assigned</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($leads as $l)
                    <tr><td class="px-6 py-3 text-sm">{{ $l->name }}</td><td class="px-6 py-3"><x-badge :label="$l->status" /></td><td class="px-6 py-3"><x-badge :label="$l->priority" /></td><td class="px-6 py-3 text-sm">{{ $l->expected_value ? '$'.number_format($l->expected_value,2) : '—' }}</td><td class="px-6 py-3 text-sm text-gray-600">{{ $l->assignedUser?->name ?? '—' }}</td></tr>
                @endforeach
            </tbody>
        </table>
        @if($leads->hasPages()) <div class="px-6 py-3 border-t">{{ $leads->links() }}</div> @endif
    </div>
</div>
@endsection
