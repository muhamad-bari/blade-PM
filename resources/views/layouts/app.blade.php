<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Muhamad-Bari') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        if (localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>

<body class="h-full font-sans antialiased bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 flex overflow-hidden"
      x-data="{ 
          sidebarOpen: false,
          darkMode: localStorage.getItem('darkMode') === 'true',
          toggleDarkMode() {
              this.darkMode = !this.darkMode;
              localStorage.setItem('darkMode', this.darkMode);
              if (this.darkMode) document.documentElement.classList.add('dark');
              else document.documentElement.classList.remove('dark');
          }
      }">

    <aside class="hidden md:flex w-64 flex-col flex-shrink-0 bg-[#343a40] dark:bg-gray-800 border-r border-gray-700 transition-width duration-300">
        <div class="h-16 flex items-center justify-center border-b border-gray-600 dark:border-gray-700 bg-[#343a40] dark:bg-gray-800 shadow-sm flex-shrink-0">
            <a href="{{ route('dashboard') }}" class="text-xl font-bold tracking-wide text-white">
                Project Management
            </a>
        </div>
        
        <div class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-2">
            @include('layouts.sidebar_content')
        </div>
    </aside>

    <div x-show="sidebarOpen" class="relative z-50 md:hidden" role="dialog" aria-modal="true">
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/80" 
             @click="sidebarOpen = false"></div>

        <div class="fixed inset-0 flex">
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition ease-in-out duration-300 transform"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-300 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="relative mr-16 flex w-full max-w-xs flex-1">
                
                <div class="flex w-full flex-col bg-[#343a40] dark:bg-gray-800 pb-4 pt-5 shadow-xl">
                    <div class="flex items-center justify-between px-4 pb-4 border-b border-gray-700">
                        <span class="text-white font-bold text-lg">Menu</span>
                        <button @click="sidebarOpen = false" class="text-gray-200 hover:text-white">
                            <i class="fa-solid fa-times text-xl"></i>
                        </button>
                    </div>
                    <div class="mt-5 flex-1 h-0 overflow-y-auto px-2">
                        @include('layouts.sidebar_content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-1 flex-col min-w-0 overflow-hidden">
        
        <header class="flex h-16 flex-shrink-0 items-center justify-between gap-x-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 shadow-sm lg:px-6 z-30">
            
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-700 md:hidden">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                
                <div class="hidden sm:block">
                     @if(request()->routeIs('projects.index') || request()->routeIs('employees.index'))
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                            </span>
                            <input id="globalSearch" class="form-input py-1.5 pl-10 pr-4 block w-64 rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" type="text" placeholder="Search...">
                        </div>
                     @endif
                </div>
            </div>

            <div class="flex items-center gap-x-4 lg:gap-x-6">
                <button @click="toggleDarkMode()" class="text-gray-400 hover:text-gray-500">
                    <i class="fa-solid" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                </button>

                <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-200 dark:lg:bg-gray-700" aria-hidden="true"></div>

                <div x-data="{ dropdownOpen: false }" class="relative">
                    <button @click="dropdownOpen = !dropdownOpen" class="flex items-center gap-x-2 p-1.5">
                        <img class="h-8 w-8 rounded-full bg-gray-50 object-cover" src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=random" alt="">
                        <span class="hidden lg:flex lg:items-center">
                            <span class="ml-2 text-sm font-semibold leading-6 text-gray-900 dark:text-white" aria-hidden="true">{{ Auth::user()->name }}</span>
                            <i class="fa-solid fa-chevron-down ml-2 text-xs text-gray-400"></i>
                        </span>
                    </button>
                    
                    <div x-show="dropdownOpen" @click.outside="dropdownOpen = false" class="absolute right-0 z-10 mt-2.5 w-32 origin-top-right rounded-md bg-white dark:bg-gray-800 py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none" style="display: none;">
                        @if(auth()->user()->role !== 'guest')
                        <a href="{{ route('profile.edit') }}" class="block px-3 py-1 text-sm leading-6 text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">Profile</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-3 py-1 text-sm leading-6 text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">Log Out</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto custom-scrollbar p-6">
            {{ $slot }}
        </main>

        <footer class="border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 py-4 text-center text-sm text-gray-500">
             &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </footer>
    </div>
</body>
</html>