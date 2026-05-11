@props(['title', 'value', 'icon', 'color' => 'indigo', 'subtitle' => null])

@php
    $colors = [
        'indigo'  => 'from-indigo-500 to-indigo-600 shadow-indigo-500/25',
        'emerald' => 'from-emerald-500 to-emerald-600 shadow-emerald-500/25',
        'amber'   => 'from-amber-500 to-amber-600 shadow-amber-500/25',
        'rose'    => 'from-rose-500 to-rose-600 shadow-rose-500/25',
        'sky'     => 'from-sky-500 to-sky-600 shadow-sky-500/25',
        'violet'  => 'from-violet-500 to-violet-600 shadow-violet-500/25',
    ];
@endphp

<div class="bg-white rounded-xl border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-300">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500">{{ $title }}</p>
            <p class="mt-1 text-3xl font-bold text-gray-900">{{ $value }}</p>
            @if ($subtitle)
                <p class="mt-1 text-xs text-gray-400">{{ $subtitle }}</p>
            @endif
        </div>
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $colors[$color] ?? $colors['indigo'] }} shadow-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
            </svg>
        </div>
    </div>
</div>
