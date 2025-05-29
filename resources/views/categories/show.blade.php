<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Category Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $category->name }}</h3>
                    <div class="mt-4">
                        <p class="text-gray-600">
                            {{ $category->description ?? 'No description available.' }}
                        </p>
                    </div>
                </div>

                <div class="mt-8">
                    <h4 class="text-md font-semibold text-gray-700 mb-4">Events in this category</h4>

                    @if($category->events && $category->events->count() > 0)
                    <ul class="list-disc pl-5">
                        @foreach($category->events as $event)
                        <li class="mb-2">
                            <a href="{{ route('events.show', $event->id) }}" class="text-blue-600 hover:text-blue-800">
                                {{ $event->title }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-gray-500">No events associated with this category.</p>
                    @endif
                </div>

                <div class="mt-8 flex gap-4">
                    <a href="{{ route('categories.edit', $category) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        Edit Category
                    </a>
                    <a href="{{ route('categories') }}" class="nline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        Back to Categories
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>