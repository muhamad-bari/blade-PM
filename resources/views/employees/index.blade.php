<x-app-layout>
    {{-- DataTables CSS & jQuery --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    
    <style>
        /* --- CSS SAMA SEPERTI PROJECTS --- */
        
        /* Ikon Lingkaran Biru */
        table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control:before,
        table.dataTable.dtr-inline.collapsed > tbody > tr > th.dtr-control:before {
            content: '+';
            font-family: 'Courier New', Courier, monospace;
            font-size: 18px;
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
            z-index: 10;
        }

        table.dataTable.dtr-inline.collapsed > tbody > tr.parent > td.dtr-control:before {
            content: '-';
            background-color: #6c757d;
        }

        /* Padding kiri agar teks tidak nabrak ikon (+) saat mobile */
        table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control,
        table.dataTable.dtr-inline.collapsed > thead > tr > th.dtr-control {
            padding-left: 45px !important;
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

        /* Hide Default DataTables Elements */
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_length { display: none; }
        
        /* Pagination Styling */
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
                    Employees
                </h3>
                
                @if(auth()->user()->role === 'admin')
                <button x-data x-on:click="$dispatch('open-modal', 'create-employee')" class="w-full md:w-auto flex justify-center items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors shadow-sm text-sm">
                    <i class="fa-solid fa-plus mr-2"></i>
                    <span>Add Employee</span>
                </button>
                @endif
            </div>

            <div class="p-4">
                <table id="employeesTable" class="display nowrap w-full text-left border-collapse stripe hover" style="width:100%">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            {{-- PERBAIKAN 1: Tambahkan min-width agar kolom Nama punya ruang --}}
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider" style="min-width: 200px;">Name</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Position</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Phone</th>
                            @if(auth()->user()->role === 'admin')
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                        @foreach($employees as $employee)
                            <tr>
                                <td class="px-4 py-3 align-middle">
                                    <div class="flex items-center gap-3">
                                        <x-avatar :src="$employee->avatar_url" :alt="$employee->name" size="xs" />
                                        <div class="font-bold text-gray-900 dark:text-gray-100">{{ $employee->name }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 align-middle text-sm text-gray-600 dark:text-gray-300">
                                    {{ $employee->position }}
                                </td>
                                <td class="px-4 py-3 align-middle text-sm text-gray-600 dark:text-gray-300">
                                    {{ $employee->email }}
                                </td>
                                <td class="px-4 py-3 align-middle text-sm text-gray-600 dark:text-gray-300">
                                    {{ $employee->phone }}
                                </td>
                                @if(auth()->user()->role === 'admin')
                                <td class="px-4 py-3 align-middle text-right">
                                    <div class="action-buttons flex justify-end gap-2">
                                        <button type="button" class="btn-edit text-gray-400 hover:text-blue-600 transition-colors" 
                                            onclick="window.location.href='{{ route('employees.edit', $employee) }}'">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        
                                        <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this employee?');">
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
            var table = $('#employeesTable').DataTable({
                // Aktifkan scroll horizontal untuk keamanan
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
                    emptyTable: "No employees found",
                    paginate: {
                        previous: '<i class="fa-solid fa-chevron-left"></i>',
                        next: '<i class="fa-solid fa-chevron-right"></i>'
                    }
                },
                // Tambahkan columnDefs di sini jika diperlukan (seperti kode sebelumnya)
                columnDefs: [
                    { responsivePriority: 0, targets: 0 },
                    { responsivePriority: 1, targets: -1 }
                ]
            });

            // Re-adjust saat resize agar header sinkron
            $(window).on('resize', function () {
                table.columns.adjust();
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