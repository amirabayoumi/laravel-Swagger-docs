<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    /**
     * Display a listing of the stories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stories = Story::with(['user', 'comments.user'])->latest()->paginate(10);
        return view('stories.index', compact('stories'));
    }

    /**
     * Show the form for creating a new story.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('stories.create');
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
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'is_published' => 'boolean',
        ]);

        if (!isset($validated['is_published'])) {
            $validated['is_published'] = false;
        }

        Story::create($validated);

        return redirect()->route('stories.index')
            ->with('success', 'Story created successfully.');
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
        return view('stories.show', compact('story'));
    }

    /**
     * Show the form for editing the specified story.
     *
     * @param  \App\Models\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function edit(Story $story)
    {
        return view('stories.edit', compact('story'));
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
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_published' => 'boolean',
        ]);

        if (!isset($validated['is_published'])) {
            $validated['is_published'] = false;
        }

        $story->update($validated);

        return redirect()->route('stories.index')
            ->with('success', 'Story updated successfully.');
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

        return redirect()->route('stories.index')
            ->with('success', 'Story deleted successfully.');
    }
}
