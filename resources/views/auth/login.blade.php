<x-guest-layout>
    <!-- Logo / Brand -->
    <div class="text-center mb-8">
        {{-- Logo --}}
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full overflow-hidden bg-white shadow-lg shadow-indigo-500/30 mb-5">
        <img src="{{ asset('images/logologin.jpg') }}" 
             alt="Logo" 
             class="w-full h-full object-cover">
    </div>

        <h1 class="text-2xl font-bold text-white tracking-tight">
            Customer Relationship Management
        </h1>
        <p class="mt-2 text-sm text-indigo-200/70">
            Sign in to access the CRM dashboard
        </p>
    </div>

    <!-- Glass Card -->
    <div class="glass-card rounded-2xl p-8 sm:p-10">

        <!-- Session Status -->
        <x-auth-session-status
            class="mb-6 rounded-lg bg-emerald-500/10 border border-emerald-400/20 px-4 py-3 text-emerald-300 text-sm"
            :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email Address')"
                    class="text-indigo-100/80 text-sm font-medium mb-2" />

                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                        <svg class="h-5 w-5 text-indigo-300/50" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                        </svg>
                    </div>
                    <x-text-input
                        id="email"
                        class="block w-full pl-12 pr-4 py-3.5 rounded-xl font-sans
                               bg-white/[0.06] border border-white/[0.1]
                               text-white placeholder-indigo-300/40
                               focus:bg-white/[0.1] focus:border-indigo-400/50 focus:ring-2 focus:ring-indigo-500/30
                               transition duration-200"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autofocus
                        autocomplete="off"
                        placeholder="example@crm.com" />
                </div>

                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-300 text-sm" />
            </div>

            <!-- Password -->
            <div class="mt-5">
                <div class="flex items-center justify-between mb-2">
                    <x-input-label for="password" :value="__('Password')"
                        class="text-indigo-100/80 text-sm font-medium" />

                    @if (Route::has('password.request'))
                        <a class="text-xs text-indigo-300/70 hover:text-indigo-200 transition-colors duration-200 focus:outline-none focus:ring-1 focus:ring-indigo-400 rounded"
                           href="{{ route('password.request') }}">
                            {{ __('Forgot password?') }}
                        </a>
                    @endif
                </div>

                <div class="relative">
                    {{-- Lock icon on the left --}}
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                        <svg class="h-5 w-5 text-indigo-300/50" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                    </div>

                    {{-- Password input --}}
                    <x-text-input
                        id="password"
                        class="block w-full pl-12 pr-12 py-3.5 rounded-xl font-sans
                               bg-white/[0.06] border border-white/[0.1]
                               text-white placeholder-indigo-300/40
                               focus:bg-white/[0.1] focus:border-indigo-400/50 focus:ring-2 focus:ring-indigo-500/30
                               transition duration-200"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••" />

                    {{-- 
                        Eye toggle button — ONE button, ONE set of icons.
                        hidden class added here so JS can remove it when user types.
                        eye-open  = shown when password is hidden (click to reveal)
                        eye-closed = shown when password is visible (click to hide)
                    --}}
                    <button type="button"
                        id="toggle-password"
                        aria-label="Toggle password visibility"
                        class="hidden absolute inset-y-0 right-0 flex items-center pr-4
                               text-indigo-300/50 hover:text-indigo-200
                               transition-colors duration-200 focus:outline-none">

                        {{-- Eye open: visible by default inside the button --}}
                        <svg id="eye-open" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>

                        {{-- Eye closed: hidden by default inside the button --}}
                        <svg id="eye-closed" class="h-5 w-5 hidden" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>

                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-300 text-sm" />
            </div>

            <!-- Remember Me -->
            <div class="mt-5">
                <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                    <input id="remember_me"
                           type="checkbox"
                           name="remember"
                           class="w-4 h-4 rounded bg-white/[0.06] border-white/[0.15]
                                  text-indigo-500 shadow-sm
                                  focus:ring-2 focus:ring-indigo-500/30 focus:ring-offset-0
                                  transition duration-200 cursor-pointer">
                    <span class="ms-3 text-sm text-indigo-200/60 group-hover:text-indigo-200/80 transition-colors duration-200 select-none">
                        {{ __('Remember me') }}
                    </span>
                </label>
            </div>

            <!-- Login Button -->
            <div class="mt-7">
                <button type="submit"
                    class="w-full inline-flex items-center justify-center py-3.5 rounded-xl
                           text-sm font-semibold text-white tracking-wide
                           bg-gradient-to-r from-indigo-500 to-purple-600
                           hover:from-indigo-400 hover:to-purple-500
                           focus:outline-none focus:ring-2 focus:ring-indigo-400/50 focus:ring-offset-0
                           shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40
                           transform hover:-translate-y-0.5 active:translate-y-0
                           transition-all duration-200">
                    <svg class="w-4 h-4 me-2 -ms-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                    </svg>
                    {{ __('Sign In') }}
                </button>
            </div>
        </form>

        <!-- Security Notice -->
        <div class="mt-8 pt-6 border-t border-white/[0.06]">
            <div class="flex items-center justify-center gap-2 text-indigo-300/40">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                </svg>
                <span class="text-xs">Authorized access only &mdash; Contact your admin for access</span>
            </div>
        </div>
    </div>

    {{-- Password toggle script --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.getElementById('password');
        const toggleBtn     = document.getElementById('toggle-password');
        const eyeOpen       = document.getElementById('eye-open');
        const eyeClosed     = document.getElementById('eye-closed');

        // Button is already hidden via the 'hidden' class on the button itself.
        // Show/hide the button based on whether the field has a value.
        passwordInput.addEventListener('input', function () {
            if (passwordInput.value.length > 0) {
                toggleBtn.classList.remove('hidden');
            } else {
                toggleBtn.classList.add('hidden');
                // Reset to password type and restore open-eye icon
                passwordInput.type = 'password';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            }
        });

        // Toggle between showing and hiding the password
        toggleBtn.addEventListener('click', function () {
            if (passwordInput.type === 'password') {
                // Reveal password → show closed eye (so user knows clicking hides it)
                passwordInput.type = 'text';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            } else {
                // Hide password → show open eye (so user knows clicking reveals it)
                passwordInput.type = 'password';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            }
        });
    });
    </script>

</x-guest-layout>