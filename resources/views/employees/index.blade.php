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
        }
    </style>

    <div class="h-[calc(100vh-65px)] flex flex-col">
        <div class="flex justify-between items-center mb-4 px-1">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Employees</h2>
        </div>

        @if(session('success'))
            <div class="mb-4 px-4 py-3 bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg flex-grow overflow-hidden flex flex-col">
            <div class="w-full h-full flex flex-col">
                <table id="employeesTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 table-auto stripe hover" style="width:100%">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                        <th class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Position</th>
                        <th class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                        <th class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($employees as $employee)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors border-b border-gray-200 dark:border-gray-700">
                            <td class="px-6 py-4 w-full md:w-auto">
                                <div class="flex items-center">
                                    <x-avatar :src="$employee->avatar_url" :alt="$employee->name" size="sm" />
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $employee->name }}</div>
                                        <div class="md:hidden text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $employee->position }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex items-center justify-between md:justify-start w-full">
                                    <span class="md:hidden text-xs font-medium text-gray-500 dark:text-gray-400">Position:</span>
                                    <span>{{ $employee->position }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex items-center justify-between md:justify-start w-full">
                                    <span class="md:hidden text-xs font-medium text-gray-500 dark:text-gray-400">Email:</span>
                                    <span>{{ $employee->email }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex items-center justify-between md:justify-start w-full">
                                    <span class="md:hidden text-xs font-medium text-gray-500 dark:text-gray-400">Phone:</span>
                                    <span>{{ $employee->phone }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2 w-full pt-2 md:pt-0 border-t md:border-t-0 border-gray-100 dark:border-gray-700 mt-2 md:mt-0">
                                    <a href="{{ route('employees.edit', $employee) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">Edit</a>
                                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
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
            var table = $('#employeesTable').DataTable({
                responsive: true,
                paging: false, // Disable paging for infinite scroll feel
                scrollY: 'calc(100vh - 250px)', // Adjust based on header/footer height
                scrollCollapse: true,
                dom: 'lrtip', // Hide default search box
                ordering: true,
                info: false,
                language: {
                    emptyTable: "No employees found"
                }
            });

            // Connect Global Search Input to DataTables
            $('#globalSearch').on('keyup', function() {
                table.search(this.value).draw();
            });
        });
    </script>

    {{-- Create Employee Modal --}}
    <x-modal name="create-employee" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Add Employee</h2>
            
            @if ($errors->any())
                <div class="mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 px-4 py-3 rounded relative">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <div x-init="$dispatch('open-modal', 'create-employee')"></div>
            @endif

            <form action="{{ route('employees.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Position</label>
                        <input type="text" name="position" value="{{ old('position') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Avatar URL</label>
                        <input type="url" name="avatar_url" value="{{ old('avatar_url') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-2">
                    <button type="button" x-on:click="$dispatch('close-modal', 'create-employee')" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 text-sm font-medium transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-medium transition-colors">Save Employee</button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>
