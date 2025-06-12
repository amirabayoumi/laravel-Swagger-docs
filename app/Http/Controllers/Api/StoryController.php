<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Story;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Add this import

class StoryController extends Controller
{
    use AuthorizesRequests; // Add this trait

    /**
     * Display a listing of all stories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stories = Story::with(['user', 'comments.user'])->where('is_published', true)->get();

        // Add user_name to story and comments
        $stories = $stories->map(function ($story) {
            $story->user_name = $story->user?->name;
            $story->comments->map(function ($comment) {
                $comment->user_name = $comment->user?->name;
                return $comment;
            });
            return $story;
        });

        return response()->json($stories);
    }

    /**
     * Store a newly created story in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_published' => 'boolean',
        ]);

        $story = Story::create([
            'user_id' => $validated['user_id'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'is_published' => $validated['is_published'] ?? false,
        ]);

        return response()->json($story, 201);
    }

    /**
     * Display the specified story.
     *
     * @param  \App\Models\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function show(Story $story)
    {
        $story->load(['user', 'comments.user']);
        $story->user_name = $story->user?->name;
        $story->comments->map(function ($comment) {
            $comment->user_name = $comment->user?->name;
            return $comment;
        });
        return response()->json($story);
    }

    /**
     * Update the specified story in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Story $story)
    {
        $this->authorize('update', $story); // Optional: add policy for ownership

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'body' => 'sometimes|string',
            // Add other fields as needed
        ]);

        $story->update($validated);

        return response()->json($story);
    }

    /**
     * Remove the specified story from storage.
     *
     * @param  \App\Models\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function destroy(Story $story)
    {
        $this->authorize('delete', $story); // Optional: add policy for ownership

        $story->delete();

        return response()->json(['message' => 'Story deleted successfully']);
    }

    /**
     * Get stories by user ID.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function getStoriesByUser(User $user)
    {
        $stories = Story::where('user_id', $user->id)
            ->with('comments')
            ->get();

        return response()->json($stories);
    }
}
