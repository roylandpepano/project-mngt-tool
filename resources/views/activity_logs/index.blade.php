<x-app-layout>
    <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Logs') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-8">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Activity Log</h1>
                    <div class="flex items-center gap-3">
                        <form method="get" class="flex items-center gap-2">
                            <input type="text" name="log_name" placeholder="Log name" value="{{ request('log_name') }}" class="px-3 py-2 border rounded-md text-sm" />
                            <input type="text" name="event" placeholder="Event" value="{{ request('event') }}" class="px-3 py-2 border rounded-md text-sm" />
                            <button class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm rounded-lg shadow-sm hover:bg-blue-700 transition">Filter</button>
                        </form>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-4 font-semibold text-gray-600 dark:text-gray-300">Date</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 dark:text-gray-300">Event</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 dark:text-gray-300">Log name</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 dark:text-gray-300">Causer</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 dark:text-gray-300">Subject</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 dark:text-gray-300 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach($activities as $activity)
                                @php
                                    $causerName = optional($activity->causer)->name ?? optional($activity->causer)->id ?? '-';
                                    $subjectLabel = optional($activity->subject)->id ? optional($activity->subject)->id . ' (' . class_basename($activity->subject_type) . ')' : '- (' . class_basename($activity->subject_type) . ')';
                                @endphp
                                <tr data-activity-id="{{ $activity->id }}"
                                    data-activity-event="{{ $activity->event }}"
                                    data-activity-logname="{{ $activity->log_name }}"
                                    data-activity-causer="{{ $causerName }}"
                                    data-activity-subject="{{ $subjectLabel }}"
                                    data-activity-created="{{ $activity->created_at }}"
                                    data-activity-properties='@json($activity->properties->toArray())'
                                    class="transition text-gray-600 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer" tabindex="0" role="button">
                                    <td class="px-6 py-4">{{ $activity->created_at }}</td>
                                    <td class="px-6 py-4">{{ $activity->event }}</td>
                                    <td class="px-6 py-4">{{ $activity->log_name }}</td>
                                    <td class="px-6 py-4">{{ $causerName }}</td>
                                    <td class="px-6 py-4">{{ $subjectLabel }}</td>
                                    <td class="px-6 py-4 flex justify-center items-center gap-2 actions">
                                        <button type="button" class="inline-flex items-center justify-center p-2 text-sm font-medium text-blue-600 bg-blue-100 dark:bg-blue-900 dark:text-blue-200 rounded hover:bg-blue-200 dark:hover:bg-blue-800 transition" title="View details" data-open-activity>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <div class="text-sm text-gray-600 dark:text-gray-300">
                        @if ($activities->total())
                           {{ $activities->links() }}
                        @else
                            No logs found.
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity details modal -->
    <div id="activity-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" data-close-activity></div>
        <div class="relative max-w-2xl w-full bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <h3 id="activity-modal-title" class="text-lg font-medium text-gray-900 dark:text-gray-100">Activity details</h3>
                    <button type="button" data-close-activity class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">&times;</button>
                </div>

                <div class="mt-4 space-y-3 text-sm text-gray-700 dark:text-gray-200">
                    <div><span class="font-semibold">Date:</span> <span id="modal-activity-created"></span></div>
                    <div><span class="font-semibold">Event:</span> <span id="modal-activity-event"></span></div>
                    <div><span class="font-semibold">Log name:</span> <span id="modal-activity-logname"></span></div>
                    <div><span class="font-semibold">Causer:</span> <span id="modal-activity-causer"></span></div>
                    <div><span class="font-semibold">Subject:</span> <span id="modal-activity-subject"></span></div>
                    <div>
                        <span class="font-semibold">Properties:</span>
                        <pre id="modal-activity-properties" class="mt-2 p-3 bg-gray-50 dark:bg-gray-900 rounded text-xs overflow-auto max-h-64 whitespace-pre-wrap"></pre>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button" data-close-activity class="ml-2 inline-flex items-center gap-2 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-300">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const modal = document.getElementById('activity-modal');
            const modalCreated = document.getElementById('modal-activity-created');
            const modalEvent = document.getElementById('modal-activity-event');
            const modalLogname = document.getElementById('modal-activity-logname');
            const modalCauser = document.getElementById('modal-activity-causer');
            const modalSubject = document.getElementById('modal-activity-subject');
            const modalProperties = document.getElementById('modal-activity-properties');

            function openModal(activity) {
                modalCreated.textContent = activity.created;
                modalEvent.textContent = activity.event;
                modalLogname.textContent = activity.logname;
                modalCauser.textContent = activity.causer;
                modalSubject.textContent = activity.subject;
                try {
                    modalProperties.textContent = JSON.stringify(activity.properties, null, 2);
                } catch (e) {
                    modalProperties.textContent = activity.properties;
                }
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeModal() {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }

            // Open when row clicked, but ignore clicks on action controls
            document.querySelectorAll('tbody tr[data-activity-id]').forEach(row => {
                row.addEventListener('click', function(e) {
                    if (e.target.closest('.actions')) return;
                    const activity = {
                        created: row.getAttribute('data-activity-created'),
                        event: row.getAttribute('data-activity-event'),
                        logname: row.getAttribute('data-activity-logname'),
                        causer: row.getAttribute('data-activity-causer'),
                        subject: row.getAttribute('data-activity-subject'),
                        properties: JSON.parse(row.getAttribute('data-activity-properties') || '{}'),
                    };
                    openModal(activity);
                });

                row.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        const activity = {
                            created: row.getAttribute('data-activity-created'),
                            event: row.getAttribute('data-activity-event'),
                            logname: row.getAttribute('data-activity-logname'),
                            causer: row.getAttribute('data-activity-causer'),
                            subject: row.getAttribute('data-activity-subject'),
                            properties: JSON.parse(row.getAttribute('data-activity-properties') || '{}'),
                        };
                        openModal(activity);
                    }
                });
            });

            // open via action button
            document.querySelectorAll('[data-open-activity]').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const row = e.target.closest('tr[data-activity-id]');
                    if (!row) return;
                    const activity = {
                        created: row.getAttribute('data-activity-created'),
                        event: row.getAttribute('data-activity-event'),
                        logname: row.getAttribute('data-activity-logname'),
                        causer: row.getAttribute('data-activity-causer'),
                        subject: row.getAttribute('data-activity-subject'),
                        properties: JSON.parse(row.getAttribute('data-activity-properties') || '{}'),
                    };
                    openModal(activity);
                });
            });

            // close handlers
            document.querySelectorAll('[data-close-activity]').forEach(el => el.addEventListener('click', closeModal));
            document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeModal(); });
        })();
    </script>

</x-app-layout>
