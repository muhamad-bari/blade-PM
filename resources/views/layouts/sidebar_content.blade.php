<nav class="space-y-1 px-2 py-4">
    <a href="{{ route('dashboard') }}" 
       class="{{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
        <i class="fa-solid fa-house mr-3 flex-shrink-0 h-6 w-6 text-center pt-1 {{ request()->routeIs('dashboard') ? 'text-indigo-600 dark:text-white' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
        Dashboard
    </a>

    <a href="{{ route('projects.index') }}" 
       class="{{ request()->routeIs('projects.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
        <i class="fa-solid fa-folder mr-3 flex-shrink-0 h-6 w-6 text-center pt-1 {{ request()->routeIs('projects.*') ? 'text-indigo-600 dark:text-white' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
        Projects
    </a>
    
    @if(auth()->user()->role === 'admin')
    <a href="{{ route('employees.index') }}" 
       class="{{ request()->routeIs('employees.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
        <i class="fa-solid fa-users mr-3 flex-shrink-0 h-6 w-6 text-center pt-1 {{ request()->routeIs('employees.*') ? 'text-indigo-600 dark:text-white' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
        Employees
    </a>
    @endif
</nav>