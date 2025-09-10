<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Projects') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-8">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Projects</h1>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg shadow-sm hover:bg-blue-700 transition-all focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        New Project
                        </a>
                            @php $next = request('sort') === 'progress' ? 'progress_asc' : 'progress'; @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort' => $next, 'page' => null]) }}" class="px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded">Sort by progress</a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 dark:bg-green-900 text-green-700 dark:text-green-200 rounded-lg border border-green-200 dark:border-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-4 font-semibold text-gray-600 dark:text-gray-300">ID</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 dark:text-gray-300">Title</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 dark:text-gray-300">Deadline</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 dark:text-gray-300">Tasks</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 dark:text-gray-300">Progress</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 dark:text-gray-300 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach ($projects as $project)
                                <tr class="text-gray-600 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4">{{ $project->id }}</td>
                                    <td class="px-6 py-4"><a href="{{ route('projects.show', $project->id) }}" class="font-medium text-blue-600 dark:text-blue-300">{{ $project->title }}</a>
                                        <div class="text-xs text-gray-500">{{ Str::limit($project->description, 100) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ optional($project->deadline)->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4">{{ $project->tasks->count() }}</td>
                                    <td class="px-6 py-4 w-48">
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden" title="{{ $project->done_tasks_count ?? $project->tasks->where('status','done')->count() }} done / {{ $project->tasks_count ?? $project->tasks->count() }} total">
                                            @php $pct = $project->progress; $color = $pct >= 75 ? 'bg-green-500' : ($pct >= 50 ? 'bg-yellow-400' : 'bg-red-400'); @endphp
                                            <div class="h-3 {{ $color }}" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <div class="text-xs mt-1 text-gray-600 dark:text-gray-300">{{ $pct }}%</div>
                                    </td>
                                    <td class="px-6 py-4 flex gap-2 justify-center items-center">
                                        <a href="{{ route('projects.edit', $project->id) }}" class="inline-flex items-center justify-center p-2 text-sm font-medium text-green-600 bg-green-100 dark:bg-green-800 dark:text-green-200 rounded hover:bg-green-200 dark:hover:bg-green-700 transition" title="Edit project">
                                            <x-icon-edit />
                                        </a>

                                        <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this project?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center p-2 text-sm font-medium text-red-600 bg-red-100 dark:bg-red-800 dark:text-red-200 rounded hover:bg-red-200 dark:hover:bg-red-700 transition" title="Delete project">
                                                <x-icon-delete />
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 text-sm text-gray-600 dark:text-gray-300">{{ $projects->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
