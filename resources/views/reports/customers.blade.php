@extends('layouts.app')
@section('title', 'Customer Report')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('reports.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 flex items-center gap-1 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Reports
            </a>
            <h2 class="text-xl font-bold text-gray-900">Customer Report</h2>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('reports.customers.export-csv') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition" title="Export CSV">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> CSV
            </a>
            <a href="{{ route('reports.customers.export-pdf') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition" title="Export PDF">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> PDF
            </a>
        </div>
    </div>

    {{-- Status Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
            <p class="text-2xl font-bold text-gray-900">{{ $customers->total() }}</p>
            <p class="text-sm text-gray-500">Total Customers</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
            <p class="text-2xl font-bold text-emerald-600">{{ $statusCounts['active'] ?? 0 }}</p>
            <p class="text-sm text-gray-500">Active</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
            <p class="text-2xl font-bold text-gray-400">{{ $statusCounts['inactive'] ?? 0 }}</p>
            <p class="text-sm text-gray-500">Inactive</p>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Company</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Assigned</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($customers as $c)
                    <tr>
                        <td class="px-6 py-3 text-sm text-gray-800">{{ $c->full_name }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ $c->email }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ $c->company ?? '—' }}</td>
                        <td class="px-6 py-3"><x-badge :label="$c->status" /></td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ $c->assignedUser?->name ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if($customers->hasPages()) <div class="px-6 py-3 border-t">{{ $customers->links() }}</div> @endif
    </div>
</div>
@endsection
