@extends('layouts.app')
@section('title', 'User Activity Report')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('reports.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 flex items-center gap-1 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Reports
            </a>
            <h2 class="text-xl font-bold text-gray-900">User Activity Report</h2>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('reports.user-activity.export-csv') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> CSV
            </a>
            <a href="{{ route('reports.user-activity.export-pdf') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> PDF
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Role</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Customers</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Leads</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Activities</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Follow-Ups</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($users as $u)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">{{ strtoupper(substr($u->name, 0, 1)) }}</div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $u->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $u->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4"><x-badge :label="$u->role" /></td>
                        <td class="px-6 py-4 text-right text-sm font-semibold text-gray-900">{{ $u->customers_count }}</td>
                        <td class="px-6 py-4 text-right text-sm font-semibold text-gray-900">{{ $u->leads_count }}</td>
                        <td class="px-6 py-4 text-right text-sm font-semibold text-gray-900">{{ $u->activities_count }}</td>
                        <td class="px-6 py-4 text-right text-sm font-semibold text-gray-900">{{ $u->follow_ups_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
