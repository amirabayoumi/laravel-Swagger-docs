<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Story;
use App\Models\User;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    /**
     * Display a listing of all stories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stories = Story::with('comments')->where('is_published', true)->get();
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


        return response()->json(['message' => 'Story not found'], 404);
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


        $validated = $request->validate([
            'title' => 'string|max:255',
            'content' => 'string',
            'is_published' => 'boolean',
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
