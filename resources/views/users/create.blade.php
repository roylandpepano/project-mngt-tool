
<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Create User') }}
		</h2>
	</x-slot>

	<div class="py-10">
		   <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			   <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-8">
				<h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-8">Create New User</h1>

				@if ($errors->any())
					<div class="mb-6 p-4 bg-red-50 dark:bg-red-900 text-red-700 dark:text-red-200 rounded-lg border border-red-200 dark:border-red-700">
						<ul class="list-disc pl-5">
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif

				<form action="{{ route('users.store') }}" method="POST" class="space-y-6">
				@csrf
				<div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
					<div>
						<label for="name" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
						<input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-400 w-full">
					</div>

					<div>
						<label for="email" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
						<input type="email" name="email" id="email" value="{{ old('email') }}" required class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-400 w-full">
					</div>

					<div>
						<label for="password" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
						<input type="password" name="password" id="password" required class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-400 w-full">
					</div>

					<div>
						<label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
						<input type="password" name="password_confirmation" id="password_confirmation" required class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-400 w-full">
					</div>

					<div class="sm:col-span-2">
						<label for="role" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
						<select name="role" id="role" required class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-400 w-full">
							<option value="">Select a role</option>
							<option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
							<option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
						</select>
					</div>
				</div>
					<div class="flex justify-end gap-2">
						<a href="{{ route('users.index') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium rounded-lg shadow-sm hover:bg-gray-300 dark:hover:bg-gray-600 transition-all focus:outline-none focus:ring-2 focus:ring-gray-400">Cancel</a>
						<button type="submit" class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg shadow-sm hover:bg-blue-700 transition-all focus:outline-none focus:ring-2 focus:ring-blue-400">Create User</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</x-app-layout>
