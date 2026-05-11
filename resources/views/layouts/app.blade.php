<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CRM') }} — @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js" defer></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex" x-data="{ sidebarOpen: false }">

        {{-- ── Sidebar ─────────────────────────────────────────── --}}
        @include('layouts.sidebar')

        {{-- ── Main Content ────────────────────────────────────── --}}
        <div class="flex-1 flex flex-col min-w-0 lg:ml-64">

            {{-- Top Bar --}}
            <header class="bg-white border-b border-gray-200 sticky top-0 z-20">
                <div class="flex items-center justify-between px-4 sm:px-6 h-16">
                    {{-- Mobile menu toggle --}}
                    <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    <h1 class="text-lg font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>

                    {{-- User dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900">
                            <div class="w-8 h-8 rounded-full bg-indigo-500 text-white flex items-center justify-center text-xs font-bold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-transition
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="mx-4 sm:mx-6 mt-4" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)">
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span class="text-sm font-medium">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">&times;</button>
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div class="mx-4 sm:mx-6 mt-4" x-data="{ show: true }" x-show="show" x-transition>
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span class="text-sm font-medium">{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-red-500 hover:text-red-700">&times;</button>
                    </div>
                </div>
            @endif

            {{-- Page Content --}}
            <main class="flex-1 p-4 sm:p-6">
                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
