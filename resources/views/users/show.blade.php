<x-app-layout>
    <div class="max-w-2xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h1 class="text-2xl font-semibold text-gray-900 mb-6">User Details</h1>
            <div class="mb-4">
                <span class="font-semibold text-gray-700">ID:</span>
                <span class="ml-2 text-gray-900">{{ $user->id }}</span>
            </div>
            <div class="mb-4">
                <span class="font-semibold text-gray-700">Name:</span>
                <span class="ml-2 text-gray-900">{{ $user->name }}</span>
            </div>
            <div class="mb-4">
                <span class="font-semibold text-gray-700">Email:</span>
                <span class="ml-2 text-gray-900">{{ $user->email }}</span>
            </div>
            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-600 transition">Back</a>
        </div>
    </div>
</x-app-layout>