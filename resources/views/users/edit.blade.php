<x-app-layout>
    <div class="max-w-2xl mx-auto py-10 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Edit User</h1>
        <form action="{{ route('users.update', $user) }}" method="POST" class="bg-white shadow rounded-lg p-6">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="block text-gray-700">Name</label>
                <input name="name" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('name', $user->name) }}">
                @error('name') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input name="email" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('email', $user->email) }}">
                @error('email') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Password (leave blank to keep current)</label>
                <input name="password" type="password" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('password') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>
            <div class="mb-6">
                <label class="block text-gray-700">Confirm Password</label>
                <input name="password_confirmation" type="password" class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div class="flex items-center">
                <button class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-600 transition">Update</button>
                <a href="{{ route('users.index') }}" class="ml-4 inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:border-gray-600 focus:ring focus:ring-gray-200 active:bg-gray-700 transition">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>