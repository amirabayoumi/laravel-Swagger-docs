<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Comment Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Comment on story: <a href="{{ route('stories.show', $comment->story_id) }}" class="text-blue-600 hover:text-blue-800">{{ $comment->story->title }}</a></h3>
                            <div>
                                <a href="{{ route('comments.edit', $comment->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mr-2">
                                    Edit
                                </a>
                                <a href="{{ route('comments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                                    Back
                                </a>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg mb-4">
                            <p class="whitespace-pre-wrap">{{ $comment->content }}</p>
                        </div>

                        <div class="flex justify-between text-sm text-gray-600">
                            <div>
                                <p><span class="font-semibold">Created by:</span> {{ $comment->user->name }}</p>
                                <p><span class="font-semibold">Created at:</span> {{ $comment->created_at->format('F j, Y g:i a') }}</p>
                            </div>
                            <div>
                                @if($comment->updated_at->gt($comment->created_at))
                                <p><span class="font-semibold">Updated at:</span> {{ $comment->updated_at->format('F j, Y g:i a') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="border-t pt-4 mt-6">
                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Delete Comment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>