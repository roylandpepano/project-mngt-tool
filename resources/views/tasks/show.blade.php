<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Task') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-8">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-4">{{ $task->title }}</h1>

                <div class="mb-4 text-gray-700 dark:text-gray-200">Project: {{ $task->project ? $task->project->title : 'n/a' }}</div>
                <div class="mb-4 text-gray-700 dark:text-gray-200">Assignee: {{ $task->assignee ? $task->assignee->name : 'Unassigned' }}</div>
                <div class="mb-4 text-gray-700 dark:text-gray-200">Due: {{ $task->due_date ? $task->due_date->format('Y-m-d') : 'n/a' }}</div>
                <div class="mb-4 text-gray-700 dark:text-gray-200">Status: <span class="capitalize">{{ $task->status }}</span></div>

                <div class="flex justify-end gap-2">
                    @if(auth()->user()->role === 'admin' || ($task->project && $task->project->user_id == auth()->id()))
                        <a href="{{ route('projects.show', $task->project_id) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded">Back to project</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded">Back to dashboard</a>
                    @endif

                    @if(auth()->user()->role === 'admin' || ($task->project && $task->project->user_id == auth()->id()))
                        <a href="{{ route('tasks.edit', $task->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded">Edit</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
