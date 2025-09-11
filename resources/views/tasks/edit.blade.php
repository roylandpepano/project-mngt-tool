<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Task') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-8">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-8">Edit Task</h1>

                <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-6">
                        @unless(isset($canEditStatusOnly) && $canEditStatusOnly)
                        <div>
                            <label for="title" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}" required class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-400 w-full">
                        </div>

                        <div>
                            <label for="due_date" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Due date</label>
                            <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 w-full">
                        </div>

                        @if(auth()->user()->role === 'admin')
                        <div>
                            <label for="assigned_to" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Assignee</label>
                            <select name="assigned_to" id="assigned_to" class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 w-full">
                                <option value="">Unassigned</option>
                                @foreach(\App\Models\User::orderBy('name')->get() as $u)
                                    <option value="{{ $u->id }}" {{ $task->assigned_to == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        @endunless

                        {{-- status is always editable for assignees and owners/admins --}}
                        <div>
                            <label for="status" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <select name="status" id="status" class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 w-full">
                                <option value="todo" {{ $task->status=='todo' ? 'selected' : '' }}>todo</option>
                                <option value="in progress" {{ $task->status=='in progress' ? 'selected' : '' }}>in progress</option>
                                <option value="done" {{ $task->status=='done' ? 'selected' : '' }}>done</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('projects.show', $task->project_id) }}" class="inline-flex items-center px-5 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium rounded-lg shadow-sm hover:bg-gray-300 dark:hover:bg-gray-600 transition-all focus:outline-none focus:ring-2 focus:ring-gray-400">Cancel</a>
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg shadow-sm hover:bg-blue-700 transition-all focus:outline-none focus:ring-2 focus:ring-blue-400">Update Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
