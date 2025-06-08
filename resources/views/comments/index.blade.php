<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Comments Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                    @endif

                    <div class="flex justify-end mb-4">
                        <a href="{{ route('comments.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Add New Comment</a>
                    </div>

                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Story</th>
                                <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Content</th>
                                <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created At</th>
                                <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Author</th>
                                <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($comments as $comment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">{{ $comment->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">{{ $comment->story->title }}</td>
                                <td class="px-6 py-4 border-b border-gray-200">{{ \Illuminate\Support\Str::limit($comment->content, 50) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">{{ $comment->created_at->format('Y-m-d H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">{{ $comment->user_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                    <a href="{{ route('comments.show', $comment->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                    <a href="{{ route('comments.edit', $comment->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $comments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>