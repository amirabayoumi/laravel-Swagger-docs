<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $story->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold">Story Details</h3>
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-600">Created: {{ $story->created_at->format('F j, Y') }}</p>
                                <p class="text-sm text-gray-600">Status:
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $story->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $story->is_published ? 'Published' : 'Draft' }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <a href="{{ route('stories.edit', $story->id) }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mr-2">Edit</a>
                                <a href="{{ route('stories.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Back</a>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8 mt-4">
                        <div class="prose max-w-none">
                            {!! nl2br(e($story->content)) !!}
                        </div>
                    </div>

                    <div class="mt-8 border-t pt-6">
                        <h3 class="text-lg font-semibold mb-4">Comments ({{ $story->comments->count() }})</h3>

                        @if($story->comments->count() > 0)
                        <div class="space-y-4">
                            @foreach($story->comments as $comment)
                            <div class="p-4 border rounded-lg">
                                <div class="flex justify-between items-start">
                                    <div class="prose max-w-none">
                                        {!! nl2br(e($comment->content)) !!}
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('comments.edit', $comment->id) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500 mt-2">
                                    Posted on {{ $comment->created_at->format('F j, Y g:i a') }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-gray-500 italic">No comments yet.</p>
                        @endif

                        <div class="mt-6">
                            <a href="{{ route('comments.create', ['story_id' => $story->id]) }}" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Add Comment</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>