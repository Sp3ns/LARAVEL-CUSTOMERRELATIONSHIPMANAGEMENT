@props(['type' => 'default', 'label'])

@php
    $styles = [
        // Lead statuses
        'New'           => 'bg-blue-100 text-blue-700',
        'Contacted'     => 'bg-yellow-100 text-yellow-700',
        'Qualified'     => 'bg-purple-100 text-purple-700',
        'Proposal Sent' => 'bg-indigo-100 text-indigo-700',
        'Negotiation'   => 'bg-orange-100 text-orange-700',
        'Won'           => 'bg-emerald-100 text-emerald-700',
        'Lost'          => 'bg-red-100 text-red-700',
        // Follow-up statuses
        'Pending'       => 'bg-yellow-100 text-yellow-700',
        'In Progress'   => 'bg-blue-100 text-blue-700',
        'Completed'     => 'bg-emerald-100 text-emerald-700',
        // Priority
        'Low'           => 'bg-gray-100 text-gray-600',
        'Medium'        => 'bg-amber-100 text-amber-700',
        'High'          => 'bg-red-100 text-red-700',
        // Customer status
        'active'        => 'bg-emerald-100 text-emerald-700',
        'inactive'      => 'bg-gray-100 text-gray-600',
        // Activity types
        'call'          => 'bg-sky-100 text-sky-700',
        'email'         => 'bg-violet-100 text-violet-700',
        'meeting'       => 'bg-amber-100 text-amber-700',
        'note'          => 'bg-gray-100 text-gray-600',
        // Roles
        'admin'         => 'bg-red-100 text-red-700',
        'manager'       => 'bg-blue-100 text-blue-700',
        'sales'         => 'bg-emerald-100 text-emerald-700',
        // Default
        'default'       => 'bg-gray-100 text-gray-600',
    ];
    $style = $styles[$label] ?? $styles['default'];
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $style }}">
    {{ $label }}
</span>
