<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
            @if(isset($tasks))
            <div class="mt-6 bg-white dark:bg-gray-800 shadow rounded-xl p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Tasks assigned to you</h3>
                @if($tasks->count())
                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-3 font-semibold text-gray-600 dark:text-gray-300">Title</th>
                                <th class="px-6 py-3 font-semibold text-gray-600 dark:text-gray-300">Project</th>
                                <th class="px-6 py-3 font-semibold text-gray-600 dark:text-gray-300">Due</th>
                                <th class="px-6 py-3 font-semibold text-gray-600 dark:text-gray-300">Status</th>
                                <th class="px-6 py-3 font-semibold text-gray-600 dark:text-gray-300">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-700 dark:text-gray-200">
                            @foreach($tasks as $task)
                                <tr>
                                    <td class="px-6 py-3">{{ $task->title }}</td>
                                    <td class="px-6 py-3">{{ $task->project ? $task->project->title : '-' }}</td>
                                    <td class="px-6 py-3">{{ $task->due_date ? $task->due_date->format('Y-m-d') : 'n/a' }}</td>
                                    <td class="px-6 py-3 capitalize">{{ $task->status }}</td>
                                    <td class="px-6 py-3">
                                        <a href="{{ route('projects.show', $task->project_id) }}" class="text-blue-600 hover:underline">View project</a>
                                        @if(auth()->user()->role === 'admin' || $task->project && $task->project->user_id === auth()->id())
                                            <span class="mx-2">|</span>
                                            <a href="{{ route('tasks.edit', $task->id) }}" class="text-blue-600 hover:underline">Edit</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $tasks->links() }}</div>
                @else
                    <div class="text-gray-700 dark:text-gray-200">No tasks assigned to you.</div>
                @endif
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
