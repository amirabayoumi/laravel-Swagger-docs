<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     */
    public function index(Request $request)
    {
        $query = Event::with('categories');

        // Apply filters that match the API controller
        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->has('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->has('organizer')) {
            $query->where('organizer', 'like', '%' . $request->organizer . '%');
        }

        // Fixed date handling with validation
        if ($request->filled('start_date') && $this->isValidDate($request->start_date)) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date') && $this->isValidDate($request->end_date)) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        // Apply category filter if selected
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }

        // Apply sorting
        $query->orderBy(
            $request->get('sort_by', 'start_date'),
            $request->get('sort_direction', 'asc')
        );

        $events = $query->paginate($request->get('per_page', 15));

        return view('events.index', compact('events'));
    }

    /**
     * Check if a date string is valid for database comparison
     *
     * @param string $date
     * @return bool
     */
    private function isValidDate($date)
    {
        try {
            // Try to parse the date using Carbon
            Carbon::parse($date);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        // Pass all categories to the view
        $categories = Category::all();
        return view('events.create', compact('categories'));
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {
        // Pass the event and categories to the view
        $categories = Category::all();
        return view('events.edit', compact('event', 'categories'));
    }

    /**
     * Store a newly created event.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'organizer' => 'required|string|max:255',
            'categories' => 'nullable|array', // Ensure categories is an array
        ]);

        $validated['user_id'] = Auth::id();

        // Create the event
        $event = Event::create($validated);

        // Sync categories (attach selected categories to the event)
        if ($request->has('categories')) {
            $event->categories()->sync($request->categories);
        }

        return redirect()->route('events')
            ->with('success', 'Event created successfully.');
    }

    /**
     * Update the specified event.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'organizer' => 'required|string|max:255',
            'categories' => 'nullable|array', // Ensure categories is an array
        ]);

        // Update the event
        $event->update($validated);

        // Sync categories (update the relationship)
        $event->categories()->sync($request->categories ?? []);

        return redirect()->route('events')
            ->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified event.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('events')
            ->with('success', 'Event deleted successfully.');
    }
}
