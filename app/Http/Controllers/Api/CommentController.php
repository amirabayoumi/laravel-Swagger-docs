<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Story;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of all comments.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = Comment::with(['story', 'user'])->get();
        // Add user_name to each comment
        $comments->map(function ($comment) {
            $comment->user_name = $comment->user?->name;
            return $comment;
        });
        return response()->json($comments);
    }

    /**
     * Store a newly created comment for a story.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Story $story)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $comment = $story->comments()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
        ]);
        $comment->load(['story', 'user']);
        $comment->user_name = $comment->user?->name;

        return response()->json($comment, 201);
    }

    /**
     * Display the specified comment.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        $comment->load(['story', 'user']);
        $comment->user_name = $comment->user?->name;
        return response()->json($comment);
    }

    /**
     * Update the specified comment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment); // Optional: add policy for ownership

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update($validated);

        return response()->json($comment);
    }

    /**
     * Remove the specified comment.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment); // Optional: add policy for ownership

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }

    /**
     * Get comments by user ID.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function getCommentsByUser(User $user)
    {
        $comments = Comment::where('user_id', $user->id)
            ->with(['story', 'user'])
            ->get();

        $comments->map(function ($comment) {
            $comment->user_name = $comment->user?->name;
            return $comment;
        });

        return response()->json($comments);
    }
}
