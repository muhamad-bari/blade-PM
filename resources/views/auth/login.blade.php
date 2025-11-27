<x-guest-layout>
    <div x-data="{ showGuestModal: false }">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />

                <x-text-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-between mt-4">
                <button type="button" @click="showGuestModal = true" class="underline text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                    {{ __('Guest Login Info') }}
                </button>

                <div class="flex items-center">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <x-primary-button class="ms-3">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
            </div>
        </form>

        <!-- Guest Login Modal -->
        <div x-show="showGuestModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
             style="display: none;">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-sm mx-4" @click.away="showGuestModal = false">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Guest Login Credentials</h3>
                
                <div class="space-y-3 mb-6">
                    <div>
                        <label class="block text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</label>
                        <div class="flex items-center justify-between bg-gray-100 dark:bg-gray-700 p-2 rounded mt-1">
                            <code class="text-sm font-mono text-gray-800 dark:text-gray-200">guest@user.com</code>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Password</label>
                        <div class="flex items-center justify-between bg-gray-100 dark:bg-gray-700 p-2 rounded mt-1">
                            <code class="text-sm font-mono text-gray-800 dark:text-gray-200">user123</code>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <button type="button" 
                            @click="document.getElementById('email').value = 'guest@user.com'; document.getElementById('password').value = 'user123'; showGuestModal = false;"
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Fill Credentials & Close
                    </button>
                    <button type="button" 
                            @click="showGuestModal = false"
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
