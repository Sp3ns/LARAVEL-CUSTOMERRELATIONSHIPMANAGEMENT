{{-- ── Sidebar Overlay (Mobile) ──────────────────────────────── --}}
<div x-show="sidebarOpen" @click="sidebarOpen = false"
     class="fixed inset-0 bg-black/50 z-30 lg:hidden" x-transition.opacity></div>

{{-- ── Sidebar ─────────────────────────────────────────────────── --}}
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-40 w-64 bg-gradient-to-b from-slate-900 to-slate-800 text-white transform transition-transform duration-200 ease-in-out lg:translate-x-0">

    {{-- Brand --}}
    <div class="flex items-center gap-3 px-6 h-16 border-b border-white/10">
        <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <span class="text-lg font-bold tracking-tight">CRM</span>
        <button @click="sidebarOpen = false" class="ml-auto lg:hidden text-gray-400 hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="mt-6 px-3 space-y-1">
        @php
            $links = [
                ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1'],
                ['route' => 'customers.index', 'label' => 'Customers', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                ['route' => 'leads.index', 'label' => 'Leads', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'],
                ['route' => 'activities.index', 'label' => 'Activities', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                ['route' => 'follow-ups.index', 'label' => 'Follow-Ups', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['route' => 'reports.index', 'label' => 'Reports', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
            ];
        @endphp

        @foreach ($links as $link)
            <a href="{{ route($link['route']) }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                      {{ request()->routeIs(explode('.', $link['route'])[0] . '*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"/>
                </svg>
                {{ $link['label'] }}
            </a>
        @endforeach

        {{-- Admin-only: User Management --}}
        @if (Auth::user()->isAdmin())
            <div class="pt-4 mt-4 border-t border-white/10">
                <p class="px-3 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Administration</p>
                <a href="{{ route('users.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                          {{ request()->routeIs('users.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    User Management
                </a>
            </div>
        @endif
    </nav>
</aside>
