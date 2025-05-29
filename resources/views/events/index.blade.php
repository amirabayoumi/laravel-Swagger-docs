<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Events Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Events List</h3>
                    <a href="{{ route('events.create') }}" class="inline-flex items-center px-5 py-3 bg-green-600 border border-transparent rounded-md font-bold text-sm text-grey-500 uppercase tracking-widest hover:bg-slate-500 transition duration-150 ease-in-out shadow-md">
                        + Add New Event
                    </a>
                </div>

                @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
                @endif

                <!-- Category Filter -->
                <div class="mb-6">
                    <form action="{{ route('events') }}" method="GET"
                        class="flex flex-col sm:flex-row sm:items-end sm:space-x-4 space-y-3 sm:space-y-0">

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Category:</label>
                            <select name="category" id="category"
                                class="mt-1 w-48 py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Categories</option>
                                @php
                                $categories = \App\Models\Category::orderBy('name')->get();
                                @endphp
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex space-x-2">
                            <button type="submit"
                                class="py-2 px-4 rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Filter
                            </button>
                            <a href="{{ route('events') }}"
                                class="py-2 px-4 rounded-md text-sm font-medium text-gray-700 border border-gray-300 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Reset
                            </a>
                        </div>

                    </form>
                </div>

                @if(request('category'))
                <div class="mb-4 text-sm">
                    <span class="font-medium">Showing events in category:</span>
                    {{ \App\Models\Category::find(request('category'))->name ?? 'Unknown' }}
                    <span class="ml-2">
                        <a href="{{ route('events') }}" class="text-indigo-600 hover:text-indigo-900">(Clear filter)</a>
                    </span>
                </div>
                @endif

                <!-- Filter Form - Collapsible -->
                <div class="bg-white rounded-lg shadow-sm mb-6">
                    <div class="border-b border-gray-200 px-4 py-3 cursor-pointer flex justify-between items-center"
                        id="filterToggle">
                        <h5 class="font-medium text-gray-700">Filter Events</h5>
                        <svg xmlns="http://www.w3.org/2000/svg" id="filterChevron"
                            class="h-5 w-5 text-gray-500 transform transition-transform duration-200"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <div id="filterContent" class="transition-all duration-300 overflow-hidden" style="max-height: 0; opacity: 0;">
                        <div class="p-4">
                            <form method="GET" action="{{ route('events') }}" id="events-filter-form">
                                <!-- Preserve category filter if it exists -->
                                @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                                @endif

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                                        <input type="text" name="title" id="title"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            value="{{ request()->get('title') }}">
                                    </div>

                                    <div>
                                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                                        <input type="text" name="location" id="location"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            value="{{ request()->get('location') }}">
                                    </div>

                                    <div>
                                        <label for="organizer" class="block text-sm font-medium text-gray-700">Organizer</label>
                                        <input type="text" name="organizer" id="organizer"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            value="{{ request()->get('organizer') }}">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                                    <div>
                                        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                        <input type="date" name="start_date" id="start_date"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            value="{{ request()->get('start_date') }}">
                                    </div>

                                    <div>
                                        <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                        <input type="date" name="end_date" id="end_date"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            value="{{ request()->get('end_date') }}">
                                    </div>

                                    <div>
                                        <label for="sort_by" class="block text-sm font-medium text-gray-700">Sort By</label>
                                        <select name="sort_by" id="sort_by"
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="start_date" {{ request()->get('sort_by') == 'start_date' ? 'selected' : '' }}>Start Date</option>
                                            <option value="title" {{ request()->get('sort_by') == 'title' ? 'selected' : '' }}>Title</option>
                                            <option value="location" {{ request()->get('sort_by') == 'location' ? 'selected' : '' }}>Location</option>
                                            <option value="organizer" {{ request()->get('sort_by') == 'organizer' ? 'selected' : '' }}>Organizer</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="sort_direction" class="block text-sm font-medium text-gray-700">Direction</label>
                                        <select name="sort_direction" id="sort_direction"
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="asc" {{ request()->get('sort_direction', 'asc') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                            <option value="desc" {{ request()->get('sort_direction') == 'desc' ? 'selected' : '' }}>Descending</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="per_page" class="block text-sm font-medium text-gray-700">Per Page</label>
                                        <select name="per_page" id="per_page"
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="15" {{ request()->get('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                                            <option value="25" {{ request()->get('per_page') == 25 ? 'selected' : '' }}>25</option>
                                            <option value="50" {{ request()->get('per_page') == 50 ? 'selected' : '' }}>50</option>
                                            <option value="100" {{ request()->get('per_page') == 100 ? 'selected' : '' }}>100</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="flex space-x-2">
                                    <button type="submit"
                                        class="py-2 px-4 rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Apply Filters
                                    </button>
                                    <a href="{{ route('events') }}"
                                        class="py-2 px-4 rounded-md text-sm font-medium text-gray-700 border border-gray-300 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Reset Filters
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Add this JavaScript for toggle functionality -->
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const filterToggle = document.getElementById('filterToggle');
                        const filterContent = document.getElementById('filterContent');
                        const filterChevron = document.getElementById('filterChevron');
                        const contentInner = filterContent.querySelector('div');

                        // Check if we have any filter params to determine initial state
                        const urlParams = new URLSearchParams(window.location.search);
                        const hasFilters = ['title', 'location', 'organizer', 'start_date', 'end_date', 'sort_by', 'sort_direction', 'per_page']
                            .some(param => urlParams.has(param));

                        // Set initial state
                        function setExpanded(expanded) {
                            if (expanded) {
                                const contentHeight = contentInner.offsetHeight;
                                filterContent.style.maxHeight = contentHeight + 'px';
                                filterContent.style.opacity = '1';
                                filterChevron.classList.remove('rotate-180');
                            } else {
                                filterContent.style.maxHeight = '0';
                                filterContent.style.opacity = '0';
                                filterChevron.classList.add('rotate-180');
                            }
                        }

                        // Initialize based on filters
                        setExpanded(hasFilters);

                        filterToggle.addEventListener('click', function() {
                            const isCollapsed = filterContent.style.maxHeight === '0px' || filterContent.style.maxHeight === '0';
                            setExpanded(isCollapsed);
                        });
                    });
                </script>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organizer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categories</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                            // Get events with category filter if specified
                            if (request()->has('category') && request('category') != '') {
                            $categoryId = request('category');
                            $eventsData = \App\Models\Event::with('categories')
                            ->whereHas('categories', function($query) use ($categoryId) {
                            $query->where('category_id', $categoryId);
                            })
                            ->get();
                            } else {
                            $eventsData = isset($events) ? $events : \App\Models\Event::with('categories')->get();
                            }
                            @endphp
                            @forelse($eventsData as $event)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="border-b border-dotted border-gray-400 hover:bg-gray-100" title="{{ $event->location }}">
                                        {{ Str::limit($event->location, 10) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event->organizer }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($event->categories as $category)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $category->name }}
                                        </span>
                                        @empty
                                        <span class="text-gray-400 text-xs">No categories</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-3 gap-3">
                                        <a href="{{ route('events.show', $event->id) }}" class="text-blue-600 hover:text-blue-900" title="View Event">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        <a href="{{ route('events.edit', $event->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit Event">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 0L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('Are you sure you want to delete this event?')" title="Delete Event">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m5-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">No events found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>