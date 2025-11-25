<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">{{ $project->title }}</h2>
            <div class="flex space-x-2">
                <a href="{{ route('projects.edit', $project) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Edit</a>
                <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Delete</button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-6">
                <x-card title="Description">
                    <p class="text-gray-600 dark:text-gray-400">{{ $project->description }}</p>
                </x-card>

                <x-card title="Team Members">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($project->employees as $employee)
                            <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <x-avatar :src="$employee->avatar_url" :alt="$employee->name" />
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $employee->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $employee->position }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-card>
            </div>

            <div class="space-y-6">
                <x-card title="Details">
                    <div class="space-y-4">
                        <div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</div>
                            <div class="mt-1">
                                <x-badge :type="$project->status">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</x-badge>
                            </div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Priority</div>
                            <div class="mt-1">
                                <x-badge :type="$project->priority">{{ ucfirst($project->priority) }}</x-badge>
                            </div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Deadline</div>
                            <div class="mt-1 text-gray-900 dark:text-gray-100 font-medium">{{ $project->deadline->format('M d, Y') }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Leader</div>
                            <div class="mt-2 flex items-center">
                                <x-avatar :src="$project->leader->avatar_url" :alt="$project->leader->name" size="sm" />
                                <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $project->leader->name }}</span>
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</x-app-layout>
