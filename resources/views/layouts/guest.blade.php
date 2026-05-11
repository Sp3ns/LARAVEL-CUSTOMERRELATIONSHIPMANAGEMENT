<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CRM') }} — Sign In</title>
        <meta name="description" content="Sign in to access the {{ config('app.name', 'CRM') }} dashboard. Authorized personnel only.">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* ── Guest Layout ───────────────────────────────── */
            .guest-bg {
                min-height: 100vh;
                background: linear-gradient(135deg,
                    #1e1b4b 0%,
                    #312e81 30%,
                    #4c1d95 65%,
                    #1e1b4b 100%
                );
                position: relative;
                overflow: hidden;
            }

            /* Floating orbs  */
            .guest-bg::before,
            .guest-bg::after {
                content: '';
                position: absolute;
                border-radius: 50%;
                filter: blur(80px);
                opacity: 0.18;
                pointer-events: none;
                will-change: transform;
            }
            .guest-bg::before {
                width: 500px;
                height: 500px;
                background: #818cf8;
                top: -120px;
                right: -100px;
                animation: float-orb 20s ease-in-out infinite;
            }
            .guest-bg::after {
                width: 400px;
                height: 400px;
                background: #a78bfa;
                bottom: -80px;
                left: -80px;
                animation: float-orb 25s ease-in-out infinite reverse;
            }

            @keyframes float-orb {
                0%, 100% { transform: translate(0, 0); }
                50%       { transform: translate(30px, -40px); }
            }

            /* Subtle grid texture */
            .grid-overlay {
                position: absolute;
                inset: 0;
                background-image:
                    linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
                background-size: 60px 60px;
                pointer-events: none;
            }

            /* Glassmorphism card */
            .glass-card {
                background: rgba(255, 255, 255, 0.07);
                backdrop-filter: blur(24px) saturate(1.4);
                -webkit-backdrop-filter: blur(24px) saturate(1.4);
                border: 1px solid rgba(255, 255, 255, 0.12);
                box-shadow:
                    0 8px 32px rgba(0, 0, 0, 0.25),
                    inset 0 1px 0 rgba(255, 255, 255, 0.1);
            }
        </style>
    </head>

    <body class="antialiased" style="font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;">
        <div class="guest-bg flex flex-col items-center justify-center px-4 sm:px-6 lg:px-8 py-12">

            <!-- Grid texture overlay -->
            <div class="grid-overlay"></div>

            <!-- Login card  -->
            <div class="relative z-10 w-full max-w-md">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <p class="relative z-10 mt-10 text-center text-xs text-indigo-300/50">
                &copy; {{ date('Y') }} {{ config('app.name', 'CRM') }}. All rights reserved.
            </p>

        </div>
    </body>
</html>