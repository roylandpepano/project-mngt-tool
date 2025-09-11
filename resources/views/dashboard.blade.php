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
                                @php
                                    $status = $task->status;
                                    if ($status == 'done') {
                                        $badge = 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100';
                                    } elseif ($status == 'in progress') {
                                        $badge = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100';
                                    } else {
                                        $badge = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
                                    }
                                    $selectBase = 'px-2 py-1 rounded border focus:outline-none';
                                    $selectColors = 'bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 border-gray-300 dark:border-gray-700 ' . ($status == 'done' ? 'dark:bg-green-800 dark:text-green-100' : ($status == 'in progress' ? 'dark:bg-yellow-800 dark:text-yellow-100' : ''));
                                @endphp
                                <tr>
                                    <td class="px-6 py-3">{{ $task->title }}</td>
                                    <td class="px-6 py-3">{{ $task->project ? $task->project->title : '-' }}</td>
                                    <td class="px-6 py-3">{{ $task->due_date ? $task->due_date->format('Y-m-d') : 'n/a' }}</td>
                                    <td class="px-6 py-3">
                                        @if(auth()->user()->role === 'user' && $task->assigned_to === auth()->id())
                                            <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="inline-flex items-center">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" onchange="this.form.submit()" class="{{ $selectBase }} {{ $selectColors }}">
                                                    <option value="todo" {{ $task->status=='todo' ? 'selected' : '' }}>todo</option>
                                                    <option value="in progress" {{ $task->status=='in progress' ? 'selected' : '' }}>in progress</option>
                                                    <option value="done" {{ $task->status=='done' ? 'selected' : '' }}>done</option>
                                                </select>
                                            </form>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded text-sm font-medium {{ $badge }}">{{ ucfirst($task->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3">
                                        <a href="{{ route('tasks.show', $task->id) }}" class="text-blue-600 hover:underline mr-3">View task</a>
                                        @if(auth()->user()->role === 'admin' || $task->project && $task->project->user_id === auth()->id())
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
