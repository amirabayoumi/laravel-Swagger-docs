<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="Events API",
 *     version="1.0.0",
 *     description="API endpoints for managing events"
 * )
 *
 * @OA\Tag(
 *     name="Events",
 *     description="API Endpoints for managing events"
 * )
 */
class EventController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/events",
     *     tags={"Events"},
     *     summary="Get all events with optional filtering",
     *     @OA\Parameter(name="title", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="location", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="organizer", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="start_date", in="query", @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="end_date", in="query", @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="sort_by", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="sort_direction", in="query", @OA\Schema(type="string", enum={"asc", "desc"})),
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Event"))
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Event::query();

        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->has('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->has('organizer')) {
            $query->where('organizer', 'like', '%' . $request->organizer . '%');
        }

        if ($request->has('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        $query->orderBy(
            $request->get('sort_by', 'start_date'),
            $request->get('sort_direction', 'asc')
        );

        return EventResource::collection($query->paginate($request->get('per_page', 15)));
    }

    /**
     * @OA\Post(
     *     path="/api/events",
     *     tags={"Events"},
     *     summary="Create a new event",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/EventCreateRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Event created",
     *         @OA\JsonContent(ref="#/components/schemas/Event")
     *     )
     * )
     */
    public function store(EventRequest $request)
    {
        $event = Event::create($request->validated());
        return new EventResource($event);
    }

    /**
     * @OA\Get(
     *     path="/api/events/{id}",
     *     tags={"Events"},
     *     summary="Get a single event by ID",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Event found",
     *         @OA\JsonContent(ref="#/components/schemas/Event")
     *     ),
     *     @OA\Response(response=404, description="Event not found")
     * )
     */
    public function show(Event $event)
    {
        return new EventResource($event);
    }

    /**
     * @OA\Put(
     *     path="/api/events/{id}",
     *     tags={"Events"},
     *     summary="Update an event",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/EventCreateRequest")
     *     ),
     *     @OA\Response(response=200, description="Event updated", @OA\JsonContent(ref="#/components/schemas/Event")),
     *     @OA\Response(response=404, description="Event not found")
     * )
     */
    public function update(EventRequest $request, Event $event)
    {
        $event->update($request->validated());
        return new EventResource($event);
    }

    /**
     * @OA\Delete(
     *     path="/api/events/{id}",
     *     tags={"Events"},
     *     summary="Delete an event",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Event deleted"),
     *     @OA\Response(response=404, description="Event not found")
     * )
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return response()->json(null, 204);
    }
}
