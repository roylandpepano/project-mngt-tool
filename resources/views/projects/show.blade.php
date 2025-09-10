<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Project') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-8">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $project->title }}</h1>
                        <div class="text-sm text-gray-600 dark:text-gray-300">Deadline: {{ optional($project->deadline)->format('Y-m-d') }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">Author: {{ $project->user ? $project->user->name : ('User ID: ' . $project->user_id) }}</div>
                        <p class="mt-4 text-gray-700 dark:text-gray-200">{{ $project->description }}</p>
                    </div>
                    <div class="w-48 text-right">
                        <div class="mb-2 text-gray-700 dark:text-gray-200">Progress: {{ $progress }}%</div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                            <div class="h-3 bg-green-500" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 dark:bg-green-900 text-green-700 dark:text-green-200 rounded-lg border border-green-200 dark:border-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-4 font-semibold text-gray-600 dark:text-gray-300">Title</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 dark:text-gray-300">Due</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 dark:text-gray-300">Status</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 dark:text-gray-300 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-700 dark:text-gray-200">
                            @foreach($project->tasks as $task)
                                <tr>
                                    <td class="px-6 py-4">{{ $task->title }}</td>
                                    <td class="px-6 py-4">{{ $task->due_date ? $task->due_date->format('Y-m-d') : 'n/a' }}</td>
                                    <td class="px-6 py-4 capitalize">{{ $task->status }}</td>
                                    <td class="px-6 py-4 flex gap-2 justify-center items-center">
                                        <a href="{{ route('tasks.edit', $task->id) }}" class="inline-flex items-center justify-center p-2 text-sm font-medium text-blue-600 bg-blue-100 dark:bg-blue-800 dark:text-blue-200 rounded hover:bg-blue-200 dark:hover:bg-blue-700 transition" title="Edit task">
                                            <x-icon-edit />
                                        </a>
                                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this task?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center p-2 text-sm font-medium text-red-600 bg-red-100 dark:bg-red-800 dark:text-red-200 rounded hover:bg-red-200 dark:hover:bg-red-700 transition" title="Delete task">
                                                <x-icon-delete />
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="bg-gray-50 dark:bg-gray-900 p-6 rounded-lg">
                    <h3 class="font-semibold mb-3 text-gray-800 dark:text-gray-100">Add Task</h3>
                    <form action="{{ route('tasks.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="sm:col-span-1">
                                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                                <input name="title" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200" required>
                            </div>
                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <select name="status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                                    <option value="todo">todo</option>
                                    <option value="in progress">in progress</option>
                                    <option value="done">done</option>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Due date</label>
                                <input type="date" name="due_date" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg shadow-sm hover:bg-blue-700 transition-all focus:outline-none focus:ring-2 focus:ring-blue-400">Add Task</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
