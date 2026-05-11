@extends('layouts.app')
@section('title', 'Sales Pipeline')

@section('content')
<div class="space-y-6">
    <div>
        <a href="{{ route('reports.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 flex items-center gap-1 mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Reports
        </a>
        <h2 class="text-xl font-bold text-gray-900">Sales Pipeline</h2>
    </div>

    @php
        $stageColors = ['New' => 'bg-blue-500', 'Contacted' => 'bg-yellow-500', 'Qualified' => 'bg-purple-500', 'Proposal Sent' => 'bg-indigo-500', 'Negotiation' => 'bg-orange-500', 'Won' => 'bg-emerald-500', 'Lost' => 'bg-red-500'];
        $grandTotal = $pipeline->sum('total_value');
    @endphp

    {{-- Pipeline Visual --}}
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <div class="grid grid-cols-1 sm:grid-cols-7 gap-3">
            @foreach ($pipeline as $stage)
                <div class="text-center p-4 rounded-xl bg-gray-50 border border-gray-100">
                    <div class="w-10 h-10 rounded-full {{ $stageColors[$stage->status] ?? 'bg-gray-400' }} mx-auto mb-2 flex items-center justify-center text-white font-bold text-sm">
                        {{ $stage->count }}
                    </div>
                    <p class="text-xs font-semibold text-gray-700">{{ $stage->status }}</p>
                    <p class="text-xs text-gray-500 mt-1">${{ number_format($stage->total_value, 0) }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Summary Table --}}
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Stage</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Leads</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Total Value</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">% of Pipeline</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($pipeline as $stage)
                    <tr>
                        <td class="px-6 py-3"><x-badge :label="$stage->status" /></td>
                        <td class="px-6 py-3 text-right text-sm font-semibold text-gray-900">{{ $stage->count }}</td>
                        <td class="px-6 py-3 text-right text-sm text-gray-800">${{ number_format($stage->total_value, 2) }}</td>
                        <td class="px-6 py-3 text-right text-sm text-gray-600">{{ $grandTotal > 0 ? round($stage->total_value / $grandTotal * 100, 1) : 0 }}%</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <td class="px-6 py-3 text-sm font-bold text-gray-900">Total</td>
                    <td class="px-6 py-3 text-right text-sm font-bold text-gray-900">{{ $pipeline->sum('count') }}</td>
                    <td class="px-6 py-3 text-right text-sm font-bold text-gray-900">${{ number_format($grandTotal, 2) }}</td>
                    <td class="px-6 py-3 text-right text-sm font-bold text-gray-900">100%</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
