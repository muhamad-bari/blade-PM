<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-6">Edit Project</h2>

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
            <form action="{{ route('projects.update', $project) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                    <input type="text" name="title" id="title" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('title', $project->title) }}" required>
                    @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description', $project->description) }}</textarea>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="leader_employee_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Leader</label>
                        <select name="leader_employee_id" id="leader_employee_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            <option value="">Select Leader</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('leader_employee_id', $project->leader_employee_id) == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                            @endforeach
                        </select>
                        @error('leader_employee_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="deadline" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deadline</label>
                        <input type="date" name="deadline" id="deadline" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('deadline', $project->deadline->format('Y-m-d')) }}" required>
                        @error('deadline') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Priority</label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="priority" value="low" class="form-radio text-indigo-600" {{ old('priority', $project->priority) == 'low' ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700 dark:text-gray-300">Low</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="priority" value="medium" class="form-radio text-indigo-600" {{ old('priority', $project->priority) == 'medium' ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700 dark:text-gray-300">Medium</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="priority" value="high" class="form-radio text-indigo-600" {{ old('priority', $project->priority) == 'high' ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700 dark:text-gray-300">High</span>
                            </label>
                        </div>
                        @error('priority') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            <option value="pending" {{ old('status', $project->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ old('status', $project->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="employee_ids" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Team Members</label>
                    <select name="employee_ids[]" id="employee_ids" multiple class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm h-32">
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ in_array($employee->id, old('employee_ids', $project->employees->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $employee->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple.</p>
                    @error('employee_ids') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_pinned" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ old('is_pinned', $project->is_pinned) ? 'checked' : '' }}>
                        <span class="ml-2 text-gray-700 dark:text-gray-300">Pin Project</span>
                    </label>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('projects.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 mr-2">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Update Project</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
