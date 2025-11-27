<x-app-layout>
    {{-- 1. Alpine Plugins --}}
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    
    <style>
        /* CSS Calendar & Custom Scrollbar */
        .fc, .fc-view-harness, .fc-daygrid-body, .fc-scrollgrid-sync-table, .fc-col-header-table, .fc-daygrid-body table { width: 100% !important; }
        .dark .fc-popover { background-color: #1f2937 !important; border-color: #374151 !important; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        .dark .fc-popover-header { background-color: #374151 !important; color: #f3f4f6 !important; }
        .dark .fc-popover-body { color: #f3f4f6 !important; }
        .dark .fc-theme-standard td, .dark .fc-theme-standard th { border-color: #374151 !important; }
        .dark .fc-col-header-cell-cushion, .dark .fc-daygrid-day-number { color: #e5e7eb !important; text-decoration: none !important; }
        @media (max-width: 640px) { .fc-header-toolbar { flex-direction: column !important; gap: 0.75rem !important; } }
        
        /* Notepad Line Effect (Optional Aesthetic) */
        textarea.notepad-lines {
            background-image: linear-gradient(transparent, transparent 31px, #e5e7eb 31px, #e5e7eb 32px, transparent 32px);
            background-size: 100% 32px;
            line-height: 32px;
        }
        .dark textarea.notepad-lines {
            background-image: linear-gradient(transparent, transparent 31px, #374151 31px, #374151 32px, transparent 32px);
        }
    </style>

    {{-- Header & Jam Digital --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Dashboard</h2>
        <div class="flex items-center gap-2 text-sm font-mono text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 px-4 py-2 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
            <i class="fa-regular fa-clock text-indigo-500"></i>
            <span id="realtimeClock" class="font-semibold tracking-wide">Loading...</span>
        </div>
    </div>

    {{-- STATS CARDS (Top Row) --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
        <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="relative bg-cyan-500 dark:bg-cyan-600 rounded-lg shadow text-white overflow-hidden flex flex-col h-32">
                <div class="p-4 flex-1 relative z-10">
                    <h3 class="text-3xl font-bold">{{ $totalProjects }}</h3>
                    <p class="text-sm font-medium uppercase tracking-wider mt-1">Total Projects</p>
                </div>
                <div class="absolute right-2 top-2 z-0 opacity-20 text-7xl transform rotate-12"><i class="fa-solid fa-folder-open"></i></div>
                <button x-data x-on:click="$dispatch('open-modal', 'modal-total-projects')" class="block w-full bg-black/10 hover:bg-black/20 text-center py-1 text-xs font-medium transition-colors z-10 focus:outline-none">More info <i class="fa-solid fa-arrow-circle-right ml-1"></i></button>
            </div>
            <div class="relative bg-yellow-500 dark:bg-yellow-600 rounded-lg shadow text-white overflow-hidden flex flex-col h-32">
                <div class="p-4 flex-1 relative z-10">
                    <h3 class="text-3xl font-bold">{{ $tasksDue }}</h3>
                    <p class="text-sm font-medium uppercase tracking-wider mt-1">Tasks Due Today</p>
                </div>
                <div class="absolute right-2 top-2 z-0 opacity-20 text-7xl transform rotate-12"><i class="fa-solid fa-list-check"></i></div>
                <button x-data x-on:click="$dispatch('open-modal', 'modal-due-today')" class="block w-full bg-black/10 hover:bg-black/20 text-center py-1 text-xs font-medium transition-colors z-10 focus:outline-none">More info <i class="fa-solid fa-arrow-circle-right ml-1"></i></button>
            </div>
            <div class="relative bg-red-500 dark:bg-red-600 rounded-lg shadow text-white overflow-hidden flex flex-col h-32">
                <div class="p-4 flex-1 relative z-10">
                    <h3 class="text-3xl font-bold">{{ $overdue }}</h3>
                    <p class="text-sm font-medium uppercase tracking-wider mt-1">Overdue Projects</p>
                </div>
                <div class="absolute right-2 top-2 z-0 opacity-20 text-7xl transform rotate-12"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <button x-data x-on:click="$dispatch('open-modal', 'modal-overdue')" class="block w-full bg-black/10 hover:bg-black/20 text-center py-1 text-xs font-medium transition-colors z-10 focus:outline-none">More info <i class="fa-solid fa-arrow-circle-right ml-1"></i></button>
            </div>
        </div>
        
        {{-- Pinned Projects (Top Right) --}}
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg flex flex-col h-full lg:max-h-[140px] overflow-hidden border border-gray-100 dark:border-gray-700">
            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700">
                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-thumbtack text-indigo-500"></i> Pinned
                </h3>
                <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-indigo-900 dark:text-indigo-300">{{ $pinnedProjects->count() }}</span>
            </div>
            <div class="overflow-y-auto p-0 custom-scrollbar flex-1">
                @forelse($pinnedProjects as $project)
                    <div class="group flex items-center justify-between px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 border-b border-gray-50 dark:border-gray-700 last:border-0">
                        <div class="min-w-0 flex-1 cursor-pointer" x-on:click="$dispatch('open-modal', 'view-project-{{ $project->id }}')">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full flex-shrink-0 {{ $project->priority === 'high' ? 'bg-red-500' : ($project->priority === 'medium' ? 'bg-yellow-500' : 'bg-green-500') }}"></div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-200 truncate">{{ $project->title }}</p>
                            </div>
                            <p class="text-xs text-gray-400 pl-4">Due: {{ $project->deadline->format('M d') }}</p>
                        </div>
                        
                        {{-- HANYA ADMIN YANG BISA LIHAT TOMBOL ACTION --}}
                        @if(auth()->user()->role === 'admin')
                        <div class="flex items-center opacity-0 group-hover:opacity-100 transition-opacity ml-2">
                            <button x-on:click="$dispatch('open-modal', 'edit-project-{{ $project->id }}')" class="text-gray-400 hover:text-blue-500 p-1" title="Edit">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </button>
                            
                            <form action="{{ route('projects.unpin', $project) }}" method="POST" class="inline-block" x-on:click.stop>
                                @csrf @method('PATCH')
                                <button type="submit" class="text-gray-400 hover:text-red-500 p-1" title="Unpin">
                                    <i class="fa-solid fa-times-circle text-xs"></i>
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-gray-400 py-4"><i class="fa-regular fa-clipboard mb-2 text-2xl"></i><span class="text-xs">No pinned projects</span></div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT GRID (SPLIT COLUMN) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        
        {{-- KOLOM KIRI (STACKED CARDS) --}}
        <div class="lg:col-span-1 flex flex-col gap-6">
            
            {{-- 1. Donut Chart Card --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg flex flex-col h-fit" x-data="{ open: true }">
                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center cursor-pointer select-none" @click="open = !open">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Project Status</h3>
                    <button class="text-gray-400 hover:text-gray-600 transition-transform duration-300" :class="{ 'rotate-180': !open }">
                        <i class="fa-solid fa-chevron-up"></i>
                    </button>
                </div>
                <div x-show="open" x-collapse>
                    <div class="p-4">
                        <div class="relative h-[220px] w-full">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. NOTEPAD CARD (Pengganti Project Workload) --}}
            {{-- Logic: x-data load dari localStorage, x-init buat watch perubahan --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg flex flex-col flex-1 h-fit" 
                 x-data="{ 
                     open: true, 
                     notes: localStorage.getItem('dashboard_notes') || '' 
                 }" 
                 x-init="$watch('notes', val => localStorage.setItem('dashboard_notes', val))">
                
                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center cursor-pointer select-none bg-yellow-50 dark:bg-gray-700/50" @click="open = !open">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="fa-regular fa-note-sticky text-yellow-500"></i> Quick Notes
                    </h3>
                    <button class="text-gray-400 hover:text-gray-600 transition-transform duration-300" :class="{ 'rotate-180': !open }">
                        <i class="fa-solid fa-chevron-up"></i>
                    </button>
                </div>
                
                <div x-show="open" x-collapse>
                    <div class="p-0 h-[220px]">
                        <textarea 
                            x-model="notes" 
                            class="notepad-lines w-full h-full p-4 border-0 resize-none focus:ring-0 bg-transparent text-gray-700 dark:text-gray-200 text-sm leading-8" 
                            placeholder="Type your notes here... (Auto-saved locally)"></textarea>
                    </div>
                </div>
            </div>

        </div>

        {{-- KOLOM KANAN (KALENDER PANJANG) --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 shadow-sm rounded-lg flex flex-col h-fit" x-data="{ open: true }">
            <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center cursor-pointer select-none" @click="open = !open">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Calendar</h3>
                <button class="text-gray-400 hover:text-gray-600 transition-transform duration-300" :class="{ 'rotate-180': !open }">
                    <i class="fa-solid fa-chevron-up"></i>
                </button>
            </div>
            
            <div x-show="open" x-collapse>
                <div class="p-4">
                    {{-- Tinggi Kalender disesuaikan (530px) agar match dengan Chart + Notepad --}}
                    <div class="w-full h-[530px]">
                        <div id="calendar" class="h-full"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODALS --}}
    @foreach($pinnedProjects as $project)
        <x-modal name="view-project-{{ $project->id }}" focusable>
             <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $project->title }}</h2>
                    <button x-on:click="$dispatch('close-modal', 'view-project-{{ $project->id }}')" class="text-gray-400 hover:text-gray-500"><i class="fa-solid fa-times"></i></button>
                </div>
                <p class="text-gray-600 dark:text-gray-300 mb-4">{{ $project->description }}</p>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="block text-gray-500">Status</span><x-badge :type="$project->status">{{ ucfirst($project->status) }}</x-badge></div>
                    <div><span class="block text-gray-500">Deadline</span><span class="text-gray-800 dark:text-gray-200">{{ $project->deadline->format('d M Y') }}</span></div>
                </div>
             </div>
        </x-modal>
        <x-modal name="edit-project-{{ $project->id }}" focusable>
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Edit Project</h2>
                <form action="{{ route('projects.update', $project) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                        <input type="text" name="title" value="{{ $project->title }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm sm:text-sm" required>
                    </div>
                    <input type="hidden" name="leader_employee_id" value="{{ $project->leader_employee_id }}">
                    <input type="hidden" name="priority" value="{{ $project->priority }}">
                    <input type="hidden" name="status" value="{{ $project->status }}">
                    <input type="hidden" name="deadline" value="{{ $project->deadline->format('Y-m-d') }}">
                    <div class="mt-6 flex justify-end space-x-2">
                        <button type="button" x-on:click="$dispatch('close-modal', 'edit-project-{{ $project->id }}')" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-md">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Save Changes</button>
                    </div>
                </form>
            </div>
        </x-modal>
    @endforeach

    {{-- MODAL: TOTAL PROJECTS --}}
    <x-modal name="modal-total-projects" focusable maxWidth="4xl">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">All Projects</h2>
                <button x-on:click="$dispatch('close-modal', 'modal-total-projects')" class="text-gray-400 hover:text-gray-500"><i class="fa-solid fa-times"></i></button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Leader</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Deadline</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($listTotalProjects as $project)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $project->title }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 flex items-center gap-2">
                                <x-avatar :src="$project->leader->avatar_url" :alt="$project->leader->name" size="xs" />
                                {{ $project->leader->name }}
                            </td>
                            <td class="px-4 py-3 text-sm"><x-badge :type="$project->status">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</x-badge></td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $project->deadline->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </x-modal>

    {{-- MODAL: TASKS DUE TODAY --}}
    <x-modal name="modal-due-today" focusable maxWidth="4xl">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">Tasks Due Today</h2>
                <button x-on:click="$dispatch('close-modal', 'modal-due-today')" class="text-gray-400 hover:text-gray-500"><i class="fa-solid fa-times"></i></button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Leader</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Deadline</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($listDueProjects as $project)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $project->title }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 flex items-center gap-2">
                                <x-avatar :src="$project->leader->avatar_url" :alt="$project->leader->name" size="xs" />
                                {{ $project->leader->name }}
                            </td>
                            <td class="px-4 py-3 text-sm"><x-badge :type="$project->status">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</x-badge></td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $project->deadline->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-sm text-center text-gray-500 dark:text-gray-400">No tasks due today.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-modal>

    {{-- MODAL: OVERDUE PROJECTS --}}
    <x-modal name="modal-overdue" focusable maxWidth="4xl">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-red-600 dark:text-red-400">Overdue Projects</h2>
                <button x-on:click="$dispatch('close-modal', 'modal-overdue')" class="text-gray-400 hover:text-gray-500"><i class="fa-solid fa-times"></i></button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Leader</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Deadline</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($listOverdueProjects as $project)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $project->title }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 flex items-center gap-2">
                                <x-avatar :src="$project->leader->avatar_url" :alt="$project->leader->name" size="xs" />
                                {{ $project->leader->name }}
                            </td>
                            <td class="px-4 py-3 text-sm"><x-badge :type="$project->status">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</x-badge></td>
                            <td class="px-4 py-3 text-sm text-red-600 dark:text-red-400 font-bold">{{ $project->deadline->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-sm text-center text-gray-500 dark:text-gray-400">No overdue projects.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-modal>

    {{-- SCRIPTS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Jam Digital
            function updateTime() {
                const now = new Date();
                const dateString = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric' });
                const timeString = now.toLocaleTimeString('id-ID', { hour12: false });
                document.getElementById('realtimeClock').textContent = `${dateString} - ${timeString}`;
            }
            setInterval(updateTime, 1000); updateTime();
            
            // Donut Chart
            const ctx = document.getElementById('statusChart').getContext('2d');
            const statusCounts = @json($projectStatusCounts);
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'In Progress', 'Completed'],
                    datasets: [{
                        data: [statusCounts.pending, statusCounts.in_progress, statusCounts.completed],
                        backgroundColor: ['#d1d5db', '#3b82f6', '#22c55e'],
                        borderWidth: 0
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right', labels: { usePointStyle: true } } }, cutout: '75%' }
            });

            // Calendar
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: window.innerWidth < 768 ? 'listWeek' : 'dayGridMonth',
                headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek' },
                events: @json($calendarEvents),
                height: '100%',
                eventClick: function(info) { if (info.event.url) { window.location.href = info.event.url; info.jsEvent.preventDefault(); } }
            });
            calendar.render();
        });
    </script>
</x-app-layout>