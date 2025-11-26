<x-app-layout>
    {{-- FullCalendar CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <style>
        /* 1. FORCE FULL WIDTH (SOLUSI KURUS) */
        .fc, 
        .fc-view-harness, 
        .fc-daygrid-body, 
        .fc-scrollgrid-sync-table, 
        .fc-col-header-table, 
        .fc-daygrid-body table {
            width: 100% !important;
        }

        /* 2. DARK MODE POPOVER (SOLUSI BLANK) */
        .dark .fc-popover {
            background-color: #1f2937 !important; /* gray-800 */
            border-color: #374151 !important; /* gray-700 */
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        }
        .dark .fc-popover-header {
            background-color: #374151 !important; /* gray-700 */
            color: #f3f4f6 !important; /* gray-100 */
        }
        .dark .fc-popover-body {
            color: #f3f4f6 !important; /* gray-100 */
        }
        .dark .fc-popover-close {
            color: #9ca3af !important; /* gray-400 */
        }
        .dark .fc-popover-close:hover {
            color: #f3f4f6 !important; /* gray-100 */
        }

        /* Dark Mode List View & General Fixes */
        .dark .fc-theme-standard td, 
        .dark .fc-theme-standard th {
            border-color: #374151 !important; /* gray-700 */
        }
        .dark .fc-col-header-cell-cushion,
        .dark .fc-daygrid-day-number {
            color: #e5e7eb !important; /* gray-200 */
            text-decoration: none !important;
        }
        .dark .fc-list-day-cushion {
            background-color: #374151 !important; /* gray-700 */
            color: #e5e7eb !important; /* gray-200 */
        }
        .dark .fc-list-event:hover td {
            background-color: #4b5563 !important; /* gray-600 */
        }
        .dark .fc-list-event td {
            color: #e5e7eb !important; /* gray-200 */
            border-color: #374151 !important; /* gray-700 */
        }
        .dark .fc-theme-standard .fc-list {
            border-color: #374151 !important; /* gray-700 */
        }

        /* 3. MOBILE TOOLBAR & RESPONSIVENESS */
        @media (max-width: 640px) {
            .fc-header-toolbar {
                flex-direction: column !important;
                gap: 0.75rem !important;
                align-items: center !important;
            }
            .fc-toolbar-chunk {
                display: flex !important;
                justify-content: center !important;
                width: 100% !important;
            }
            .fc-toolbar-title {
                font-size: 1.25rem !important;
            }
            .fc-button-group {
                display: flex !important;
            }
            .fc table {
                font-size: 0.75rem !important;
            }
        }
    </style>
    {{-- Header & Actions --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Dashboard</h2>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <x-card title="Total Projects">
            <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $totalProjects }}</div>
        </x-card>
        <x-card title="Tasks Due Today">
            <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $tasksDue }}</div>
        </x-card>
        <x-card title="Overdue Projects">
            <div class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $overdue }}</div>
        </x-card>
    </div>

    {{-- Charts & Calendar Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- Donut Chart --}}
        <div class="lg:col-span-1 bg-white dark:bg-gray-800 shadow-sm rounded-lg flex flex-col" x-data="{ open: true }">
            <div class="p-6 pb-0 flex justify-between items-center cursor-pointer" @click="open = !open">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Project Status</h3>
                <button class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-transform duration-200" :class="{ 'rotate-180': !open }">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>
            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="p-6 pt-4 flex-grow">
                <div class="relative min-h-[300px]">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Calendar --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 shadow-sm rounded-lg flex flex-col" x-data="{ open: true }">
            <div class="p-6 pb-0 flex justify-between items-center cursor-pointer" @click="open = !open">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Project Calendar</h3>
                <button class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-transform duration-200" :class="{ 'rotate-180': !open }">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200" 
                 x-transition:enter-start="opacity-0 transform scale-95" 
                 x-transition:enter-end="opacity-100 transform scale-100" 
                 x-transition:leave="transition ease-in duration-100" 
                 x-transition:leave-start="opacity-100 transform scale-100" 
                 x-transition:leave-end="opacity-0 transform scale-95" 
                 class="pt-4 flex-grow overflow-hidden">
                {{-- Edge-to-edge container (px-0) with fixed height --}}
                <div class="w-full h-[500px] overflow-y-auto px-0 sm:px-2 pb-2">
                    <div id="calendar" class="w-full"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pinned Projects --}}
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Pinned Projects</h2>

        @if($pinnedProjects->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($pinnedProjects as $project)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex flex-col h-full transition-shadow duration-200 hover:shadow-md">
                        <div class="p-6 flex-grow">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 truncate pr-2" title="{{ $project->title }}">{{ $project->title }}</h3>
                                <x-badge :type="$project->priority">{{ ucfirst($project->priority) }}</x-badge>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-3">{{ $project->description }}</p>
                            <div class="flex items-center mt-4">
                                <x-avatar :src="$project->leader->avatar_url" :alt="$project->leader->name" size="sm" />
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ $project->leader->name }}</span>
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 mt-auto">
                            <div class="flex justify-between items-center">
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Due: {{ $project->deadline->format('M d') }}
                                </div>
                                <div class="flex space-x-2" x-data>
                                    {{-- Tombol View --}}
                                    <button x-on:click="$dispatch('open-modal', 'view-project-{{ $project->id }}')" 
                                        class="px-3 py-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded text-xs font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" 
                                        title="View Details">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>

                                    {{-- Tombol Edit --}}
                                    <button x-on:click="$dispatch('open-modal', 'edit-project-{{ $project->id }}')" 
                                        class="px-3 py-1 bg-indigo-600 text-white border border-transparent rounded text-xs font-medium hover:bg-indigo-700 transition-colors" 
                                        title="Edit Project">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>

                                    {{-- Tombol Unpin --}}
                                    <form action="{{ route('projects.unpin', $project) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                            class="px-3 py-1 bg-yellow-500 text-white border border-transparent rounded text-xs font-medium hover:bg-yellow-600 transition-colors" 
                                            title="Unpin Project">
                                            <i class="fa-solid fa-thumbtack"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- View Modal --}}
                    <x-modal name="view-project-{{ $project->id }}" focusable>
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $project->title }}</h2>
                                <button x-on:click="$dispatch('close-modal', 'view-project-{{ $project->id }}')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <span class="sr-only">Close</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</h4>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-200">{{ $project->description }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</h4>
                                        <div class="mt-1">
                                            <x-badge :type="$project->status">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</x-badge>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Priority</h4>
                                        <div class="mt-1">
                                            <x-badge :type="$project->priority">{{ ucfirst($project->priority) }}</x-badge>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Leader</h4>
                                    <div class="flex items-center mt-1">
                                        <x-avatar :src="$project->leader->avatar_url" :alt="$project->leader->name" size="sm" />
                                        <span class="ml-2 text-sm text-gray-900 dark:text-gray-200">{{ $project->leader->name }}</span>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Team Members</h4>
                                    <div class="mt-1 flex flex-wrap gap-2">
                                        @foreach($project->employees as $emp)
                                            <div class="flex items-center bg-gray-100 dark:bg-gray-700 rounded-full px-3 py-1">
                                                <x-avatar :src="$emp->avatar_url" :alt="$emp->name" size="xs" class="w-5 h-5" />
                                                <span class="ml-2 text-xs text-gray-700 dark:text-gray-300">{{ $emp->name }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                     <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Deadline</h4>
                                     <p class="mt-1 text-sm text-gray-900 dark:text-gray-200">{{ $project->deadline->format('F j, Y') }}</p>
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end">
                                <button x-on:click="$dispatch('close-modal', 'view-project-{{ $project->id }}')" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 text-sm font-medium transition-colors">Close</button>
                            </div>
                        </div>
                    </x-modal>

                    {{-- Edit Modal --}}
                    <x-modal name="edit-project-{{ $project->id }}" focusable>
                        <div class="p-6">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Edit Project</h2>
                            <form action="{{ route('projects.update', $project) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                                    <input type="text" name="title" value="{{ $project->title }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                    <select name="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="pending" {{ $project->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="in_progress" {{ $project->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $project->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>
                                {{-- Hidden fields for required validation if not changing --}}
                                <input type="hidden" name="leader_employee_id" value="{{ $project->leader_employee_id }}">
                                <input type="hidden" name="priority" value="{{ $project->priority }}">
                                <input type="hidden" name="deadline" value="{{ $project->deadline->format('Y-m-d') }}">

                                <div class="mt-6 flex justify-end space-x-2">
                                    <button type="button" x-on:click="$dispatch('close-modal', 'edit-project-{{ $project->id }}')" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 text-sm font-medium transition-colors">Cancel</button>
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-medium transition-colors">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </x-modal>
                @endforeach
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 text-center">
                <p class="text-gray-500 dark:text-gray-400">No pinned projects found.</p>
            </div>
        @endif
    </div>

    {{-- Create Project Modal --}}
    <x-modal name="create-project" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Create New Project</h2>
            
            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Whoops!</strong>
                    <span class="block sm:inline">Something went wrong.</span>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                {{-- Re-open modal if errors exist --}}
                <div x-init="$dispatch('open-modal', 'create-project')"></div>
            @endif

            <form action="{{ route('projects.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Leader</label>
                    <select name="leader_employee_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        <option value="">Select Leader</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('leader_employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Priority</label>
                        <select name="priority" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deadline</label>
                        <input type="date" name="deadline" value="{{ old('deadline') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select name="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="mb-4">
                     <label class="inline-flex items-center">
                        <input type="checkbox" name="is_pinned" value="1" class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ old('is_pinned') ? 'checked' : '' }}>
                        <span class="ml-2 text-gray-700 dark:text-gray-300">Pin Project</span>
                    </label>
                </div>

                <div class="mt-6 flex justify-end space-x-2">
                    <button type="button" x-on:click="$dispatch('close-modal', 'create-project')" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 text-sm font-medium transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-medium transition-colors">Create Project</button>
                </div>
            </form>
        </div>
    </x-modal>

    {{-- Scripts for Chart and Calendar --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Donut Chart
            const ctx = document.getElementById('statusChart').getContext('2d');
            const statusCounts = @json($projectStatusCounts);
            
            const chart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'In Progress', 'Completed'],
                    datasets: [{
                        data: [statusCounts.pending, statusCounts.in_progress, statusCounts.completed],
                        backgroundColor: [
                            '#d1d5db', // gray-300
                            '#93c5fd', // blue-300
                            '#86efac', // green-300
                        ],
                        borderColor: [
                            '#9ca3af', // gray-400
                            '#60a5fa', // blue-400
                            '#4ade80', // green-400
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151'
                            }
                        }
                    }
                }
            });

            // Reactivity for Chart Legend (Dark Mode)
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.attributeName === 'class') {
                        const isDark = document.documentElement.classList.contains('dark');
                        chart.options.plugins.legend.labels.color = isDark ? '#e5e7eb' : '#374151';
                        chart.update();
                    }
                });
            });

            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class']
            });

            // FullCalendar
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: window.innerWidth < 768 ? 'listWeek' : 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: @json($calendarEvents),
                height: '100%', // Fill the fixed height container
                navLinks: true,
                editable: false,
                selectable: true,
                nowIndicator: true,
                dayMaxEvents: true,
                windowResize: function(view) {
                    if (window.innerWidth < 768) {
                        calendar.changeView('listWeek');
                    } else {
                        calendar.changeView('dayGridMonth');
                    }
                },
                eventClick: function(info) {
                    if (info.event.url) {
                        window.location.href = info.event.url;
                        info.jsEvent.preventDefault();
                    }
                }
            });
            calendar.render();
        });
    </script>
</x-app-layout>
