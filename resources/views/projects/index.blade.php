<x-app-layout>
    {{-- DataTables CSS & jQuery --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <style>
        /* DataTables Customization */
        .dataTables_wrapper .dataTables_filter { display: none; } /* Hide default search */
        .dataTables_wrapper .dataTables_length { display: none; } /* Hide page length */
        .dataTables_wrapper .dataTables_paginate { display: none; } /* Hide default pagination */
        .dataTables_wrapper .dataTables_info { display: none; } /* Hide info */
        
        /* Dark Mode DataTables Overrides */
        .dark .dataTables_wrapper .dataTables_length,
        .dark .dataTables_wrapper .dataTables_filter,
        .dark .dataTables_wrapper .dataTables_info,
        .dark .dataTables_wrapper .dataTables_processing,
        .dark .dataTables_wrapper .dataTables_paginate {
            color: #e5e7eb;
        }
        .dark table.dataTable tbody tr {
            background-color: transparent;
        }
        .dark table.dataTable tbody tr:hover {
            background-color: rgba(55, 65, 81, 0.5); /* gray-700/50 */
        }
        .dark table.dataTable thead th {
            color: #d1d5db; /* gray-300 */
            border-bottom-color: #4b5563; /* gray-600 */
        }
        .dark table.dataTable.no-footer {
            border-bottom-color: #4b5563; /* gray-600 */
        }
        
        /* Fixed Header Styling */
        .dataTables_scrollHeadInner {
            width: 100% !important;
        }
        table.dataTable {
            width: 100% !important;
        }

        /* MOBILE CARD VIEW FOR DATATABLES */
        @media (max-width: 768px) {
            /* Hide the table header */
            .dataTables_scrollHead {
                display: none !important;
            }
            
            /* Make rows look like cards */
            table.dataTable tbody tr {
                display: block;
                background-color: #fff;
                border: 1px solid #e5e7eb;
                border-radius: 0.5rem;
                margin-bottom: 1rem;
                padding: 1rem;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            }
            
            .dark table.dataTable tbody tr {
                background-color: #1f2937; /* gray-800 */
                border-color: #374151; /* gray-700 */
            }

            /* Make cells display as block/flex */
            table.dataTable tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.5rem 0;
                border: none !important;
                text-align: right;
            }
            
            /* Add labels before content (optional, or rely on layout) */
            /* We will use specific classes in HTML to handle layout */
        }
    </style>

    <div class="h-[calc(100vh-65px)] flex flex-col">
        <div class="flex justify-between items-center mb-4 px-1">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Projects</h2>
        </div>

        @if(session('success'))
            <div class="mb-4 px-4 py-3 bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg flex-grow overflow-hidden flex flex-col">
            <div class="w-full h-full flex flex-col">
                <table id="projectsTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 table-auto stripe hover" style="width:100%">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Project</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Leader</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Priority</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Deadline</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($projects as $project)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors border-b border-gray-200 dark:border-gray-700">
                                <td class="px-6 py-4 w-full md:w-auto">
                                    <div class="flex flex-col">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $project->title }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-xs">{{ $project->description }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-between md:justify-start w-full">
                                        <span class="md:hidden text-xs font-medium text-gray-500 dark:text-gray-400">Leader:</span>
                                        <div class="flex items-center">
                                            <x-avatar :src="$project->leader->avatar_url" :alt="$project->leader->name" size="xs" />
                                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">{{ $project->leader->name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-between md:justify-start w-full">
                                        <span class="md:hidden text-xs font-medium text-gray-500 dark:text-gray-400">Status:</span>
                                        <x-badge :type="$project->status">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</x-badge>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-between md:justify-start w-full">
                                        <span class="md:hidden text-xs font-medium text-gray-500 dark:text-gray-400">Priority:</span>
                                        <x-badge :type="$project->priority">{{ ucfirst($project->priority) }}</x-badge>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <div class="flex items-center justify-between md:justify-start w-full">
                                        <span class="md:hidden text-xs font-medium text-gray-500 dark:text-gray-400">Deadline:</span>
                                        <span>{{ $project->deadline->format('M d, Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2 w-full pt-2 md:pt-0 border-t md:border-t-0 border-gray-100 dark:border-gray-700 mt-2 md:mt-0" x-data>
                                        <button x-on:click="$dispatch('open-modal', 'view-project-{{ $project->id }}')" 
                                            class="p-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                            title="View Details">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button x-on:click="$dispatch('open-modal', 'edit-project-{{ $project->id }}')" 
                                            class="p-2 bg-indigo-600 text-white border border-transparent rounded hover:bg-indigo-700 transition-colors"
                                            title="Edit Project">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="p-2 bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 rounded hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors"
                                                title="Delete Project">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var table = $('#projectsTable').DataTable({
                responsive: true,
                paging: false, // Disable paging for infinite scroll feel
                scrollY: 'calc(100vh - 250px)', // Adjust based on header/footer height
                scrollCollapse: true,
                dom: 'lrtip', // Hide default search box
                ordering: true,
                info: false,
                language: {
                    emptyTable: "No projects found"
                }
            });

            // Connect Global Search Input to DataTables
            $('#globalSearch').on('keyup', function() {
                table.search(this.value).draw();
            });
        });
    </script>

    {{-- Create Modal --}}
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

    {{-- Loop for View/Edit Modals --}}
    @foreach($projects as $project)
        {{-- View Modal --}}
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

        {{-- Edit Modal --}}
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
</x-app-layout>
