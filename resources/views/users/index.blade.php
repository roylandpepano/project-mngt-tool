<x-app-layout>
    <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-8">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Users</h1>
                    <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg shadow-sm hover:bg-blue-700 transition-all focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        New User
                    </a>
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
                                <th class="px-6 py-4 font-semibold text-gray-600 dark:text-gray-300">Name</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 dark:text-gray-300">Email</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 dark:text-gray-300">Role</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 dark:text-gray-300 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach ($users as $user)
                                <tr data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}" data-user-email="{{ $user->email }}" data-user-role="{{ $user->role }}"
                                    class="transition text-gray-600 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer focus:outline-none
                                    {{ $loggedInUser->id === $user->id ? 'bg-blue-200 dark:bg-blue-800' : '' }}
                                    @if($loggedInUser->id !== $user->id)
                                        {{ $loop->odd ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800' }}
                                    @endif
                                    " tabindex="0" role="button">
                                    <td class="px-6 py-4">{{ $user->id }}</td>
                                    <td class="px-6 py-4">{{ $user->name }}</td>
                                    <td class="px-6 py-4">{{ $user->email }}</td>
                                    <td class="px-6 py-4 capitalize">{{ $user->role }}</td>
                                    <td class="px-6 py-4 flex gap-2 justify-center items-center actions">
                                        <!-- View button removed; clicking the row will open a modal -->
                                        <a href="{{ route('users.edit', $user->id) }}" class="inline-flex items-center justify-center p-2 text-sm font-medium text-green-600 bg-green-100 dark:bg-green-800 dark:text-green-200 rounded hover:bg-green-200 dark:hover:bg-green-700 transition" title="Edit">
                                            <x-icon-edit />
                                        </a>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center p-2 text-sm font-medium text-red-600 bg-red-100 dark:bg-red-800 dark:text-red-200 rounded hover:bg-red-200 dark:hover:bg-red-700 transition" title="Delete">
                                                <x-icon-delete />
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    <div class="text-sm text-gray-600 dark:text-gray-300">
                        @if ($users->total())
                           {{ $users->links() }}
                        @else
                            No users found.
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User details modal -->
    <div id="user-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" data-close-modal></div>
        <div class="relative max-w-xl w-full bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <h3 id="user-modal-title" class="text-lg font-medium text-gray-900 dark:text-gray-100">User details</h3>
                    <button type="button" data-close-modal class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">&times;</button>
                </div>

                <div class="mt-4 space-y-3 text-sm text-gray-700 dark:text-gray-200">
                    <div><span class="font-semibold">ID:</span> <span id="modal-user-id"></span></div>
                    <div><span class="font-semibold">Name:</span> <span id="modal-user-name"></span></div>
                    <div><span class="font-semibold">Email:</span> <span id="modal-user-email"></span></div>
                    <div><span class="font-semibold">Role:</span> <span id="modal-user-role"></span></div>
                </div>

                <div class="mt-6 flex justify-end">
                    <a id="modal-edit-link" href="#" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Edit</a>
                    <button type="button" data-close-modal class="ml-2 inline-flex items-center gap-2 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-300">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const modal = document.getElementById('user-modal');
            const modalId = document.getElementById('modal-user-id');
            const modalName = document.getElementById('modal-user-name');
            const modalEmail = document.getElementById('modal-user-email');
            const modalRole = document.getElementById('modal-user-role');
            const modalEditLink = document.getElementById('modal-edit-link');

            function openModal(user) {
                modalId.textContent = user.id;
                modalName.textContent = user.name;
                modalEmail.textContent = user.email;
                modalRole.textContent = user.role;
                modalEditLink.href = `/users/${user.id}/edit`;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeModal() {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }

            // Open when row clicked, but ignore clicks on action controls
            document.querySelectorAll('tbody tr[data-user-id]').forEach(row => {
                row.addEventListener('click', function(e) {
                    // if click originated from within .actions (edit/delete), ignore
                    if (e.target.closest('.actions')) return;
                    const user = {
                        id: row.getAttribute('data-user-id'),
                        name: row.getAttribute('data-user-name'),
                        email: row.getAttribute('data-user-email'),
                        role: row.getAttribute('data-user-role'),
                    };
                    openModal(user);
                });

                // keyboard accessible: Enter or Space
                row.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        const user = {
                            id: row.getAttribute('data-user-id'),
                            name: row.getAttribute('data-user-name'),
                            email: row.getAttribute('data-user-email'),
                            role: row.getAttribute('data-user-role'),
                        };
                        openModal(user);
                    }
                });
            });

            // close handlers
            document.querySelectorAll('[data-close-modal]').forEach(el => el.addEventListener('click', closeModal));
            document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeModal(); });
        })();
    </script>

</x-app-layout>
