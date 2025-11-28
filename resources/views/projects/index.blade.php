<x-app-layout>
    {{-- DataTables CSS & jQuery --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    
    <style>
        /* --- CSS SAMA SEPERTI SEBELUMNYA --- */
        
        /* Ikon Lingkaran Biru */
        table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control:before,
        table.dataTable.dtr-inline.collapsed > tbody > tr > th.dtr-control:before {
            content: '+';
            font-family: 'Courier New', Courier, monospace;
            font-size: 18px;
            line-height: 18px;
            font-weight: bold;
            background-color: #0d6efd;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            width: 24px;
            height: 24px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            position: absolute;
            left: 8px; 
            top: 50%;
            transform: translateY(-50%);
        }

        table.dataTable.dtr-inline.collapsed > tbody > tr.parent > td.dtr-control:before {
            content: '-';
            background-color: #6c757d;
        }

        table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control,
        table.dataTable.dtr-inline.collapsed > thead > tr > th.dtr-control {
            padding-left: 40px !important;
        }

        .dtr-details .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: flex-start !important;
            margin-top: 5px;
        }

        .dtr-details { width: 100%; }
        .dtr-details li {
            border-bottom: 1px solid #eee;
            padding: 8px 0;
            display: flex;
            flex-direction: column;
        }
        .dark .dtr-details li { border-bottom: 1px solid #374151; }
        .dtr-title {
            font-weight: bold;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_length { display: none; }
        
        .dataTables_wrapper .dataTables_paginate {
            padding-top: 1rem;
            display: flex;
            justify-content: flex-end;
            gap: 0.25rem;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border: 1px solid #e5e7eb !important;
            background: #fff !important;
            border-radius: 0.375rem !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #4f46e5 !important;
            color: white !important;
            border-color: #4f46e5 !important;
        }
    </style>

    <div class="w-full">
        
        @if(session('success'))
            <div class="mb-4 px-4 py-3 bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
            
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row justify-between items-center gap-4 bg-white dark:bg-gray-800">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">
                    Projects
                </h3>
                
                @if(auth()->user()->role === 'admin')
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <button type="button" x-on:click="$dispatch('open-modal', 'create-project')" class="w-full md:w-auto flex justify-center items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors shadow-sm text-sm">
                        <i class="fa-solid fa-plus mr-2"></i>
                        <span>Create Project</span>
                    </button>

                    <button type="button" x-on:click="$dispatch('open-modal', 'export-project')" class="w-full md:w-auto flex justify-center items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors shadow-sm text-sm">
                        <i class="fa-solid fa-file-excel mr-2"></i>
                        <span>Export Excel</span>
                    </button>
                </div>
                @endif
            </div>

            <div class="p-4">
                <table id="projectsTable" class="display nowrap w-full text-left border-collapse stripe hover" style="width:100%">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Project</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Leader</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Priority</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Deadline</th>
                            @if(auth()->user()->role === 'admin')
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                        @foreach($projects as $project)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-gray-900 dark:text-gray-100">{{ $project->title }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[200px]">{{ $project->description }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <x-avatar :src="$project->leader->avatar_url" :alt="$project->leader->name" size="xs" />
                                        <span class="text-sm text-gray-600 dark:text-gray-300">{{ $project->leader->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <x-badge :type="$project->status">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</x-badge>
                                </td>
                                <td class="px-4 py-3">
                                    <x-badge :type="$project->priority">{{ ucfirst($project->priority) }}</x-badge>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $project->deadline->format('M d, Y') }}
                                </td>
                                @if(auth()->user()->role === 'admin')
                                <td class="px-4 py-3 text-right">
                                    <div class="action-buttons flex justify-end gap-2">
                                        <button type="button" class="btn-view text-gray-400 hover:text-indigo-600 transition-colors" data-id="{{ $project->id }}">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn-edit text-gray-400 hover:text-blue-600 transition-colors" data-id="{{ $project->id }}">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline" onsubmit="return confirm('Delete this project?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#projectsTable').DataTable({
                scrollX: true,
                autoWidth: false,
                responsive: {
                    details: {
                        type: 'column',
                        target: 0
                    }
                },
                paging: true,
                pageLength: 10,
                lengthChange: false,
                ordering: true,
                info: true,
                dom: 'rtip',
                language: {
                    emptyTable: "No projects found",
                },
            });

            $(window).on('resize', function () {
                table.columns.adjust();
            });

            // Event Delegation untuk Action Buttons
            $(document).on('click', '.btn-view', function() {
                var id = $(this).data('id');
                window.dispatchEvent(new CustomEvent('open-modal', { detail: 'view-project-' + id }));
            });

            $(document).on('click', '.btn-edit', function() {
                var id = $(this).data('id');
                window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-project-' + id }));
            });
        });
    </script>
    
    {{-- MODAL SECTION --}}
    
    <x-modal name="create-project" focusable maxWidth="xl">
        <div class="p-6 w-[95%] md:w-full max-w-2xl max-h-[90vh] overflow-y-auto mx-auto">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Create New Project</h2>
            @if ($errors->any())
                <div class="mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 px-4 py-3 rounded relative">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <div x-init="$dispatch('open-modal', 'create-project')"></div>
            @endif

            <form action="{{ route('projects.store') }}" method="POST">
                @csrf
                <div class="max-h-[90vh] overflow-y-auto space-y-4 pr-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description') }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Leader</label>
                            <select name="leader_employee_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                <option value="">Select Leader</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('leader_employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deadline</label>
                            <input type="date" name="deadline" value="{{ old('deadline') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Priority</label>
                            <select name="priority" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <select name="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Team Members</label>
                        <select name="employee_ids[]" multiple class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm h-24">
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ in_array($employee->id, old('employee_ids', [])) ? 'selected' : '' }}>{{ $employee->name }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Hold Ctrl/Cmd to select multiple.</p>
                    </div>
                    <div>
                         <label class="inline-flex items-center">
                            <input type="checkbox" name="is_pinned" value="1" class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ old('is_pinned') ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700 dark:text-gray-300">Pin Project</span>
                        </label>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-2">
                    <button type="button" x-on:click="$dispatch('close-modal', 'create-project')" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 text-sm font-medium transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-medium transition-colors">Create Project</button>
                </div>
            </form>
        </div>
    </x-modal>

    @foreach($projects as $project)
        <x-modal name="view-project-{{ $project->id }}" focusable maxWidth="xl">
            <div class="p-6 w-[95%] md:w-full max-w-2xl max-h-[90vh] overflow-y-auto mx-auto">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $project->title }}</h2>
                    <button x-on:click="$dispatch('close-modal', 'view-project-{{ $project->id }}')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="max-h-[90vh] overflow-y-auto space-y-4 pr-2">
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

        <x-modal name="edit-project-{{ $project->id }}" focusable maxWidth="xl">
            <div class="p-6 w-[95%] md:w-full max-w-2xl max-h-[90vh] overflow-y-auto mx-auto">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Edit Project</h2>
                <form action="{{ route('projects.update', $project) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="max-h-[90vh] overflow-y-auto space-y-4 pr-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                            <input type="text" name="title" value="{{ $project->title }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ $project->description }}</textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Leader</label>
                                <select name="leader_employee_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ $project->leader_employee_id == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deadline</label>
                                <input type="date" name="deadline" value="{{ $project->deadline->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Priority</label>
                                <select name="priority" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    <option value="low" {{ $project->priority == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ $project->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ $project->priority == 'high' ? 'selected' : '' }}>High</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    <option value="pending" {{ $project->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ $project->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ $project->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Team Members</label>
                            <select name="employee_ids[]" multiple class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm h-24">
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ $project->employees->contains($employee->id) ? 'selected' : '' }}>{{ $employee->name }}</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Hold Ctrl/Cmd to select multiple.</p>
                        </div>
                        <div>
                             <label class="inline-flex items-center">
                                <input type="checkbox" name="is_pinned" value="1" class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ $project->is_pinned ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700 dark:text-gray-300">Pin Project</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-2">
                        <button type="button" x-on:click="$dispatch('close-modal', 'edit-project-{{ $project->id }}')" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 text-sm font-medium transition-colors">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-medium transition-colors">Save Changes</button>
                    </div>
                </form>
            </div>
        </x-modal>
    @endforeach

    <x-modal name="export-project" focusable maxWidth="sm">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Export Projects</h2>
            <form action="{{ route('projects.export') }}" method="GET">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date</label>
                        <input type="date" name="start_date" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Date</label>
                        <input type="date" name="end_date" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Priority</label>
                        <select name="priority" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="all">All Priorities</option>
                            <option value="high">High</option>
                            <option value="medium">Medium</option>
                            <option value="low">Low</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-2">
                    <button type="button" x-on:click="$dispatch('close-modal', 'export-project')" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 text-sm font-medium transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-medium transition-colors">Download</button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>