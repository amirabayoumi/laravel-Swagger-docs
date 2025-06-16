<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of all users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $users = User::all();
        return response()->json($users);
    }

    /**
     * Display the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {


        // Load relationships if needed
        $user->load(['stories', 'comments']);

        return response()->json($user);
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {

        $this->authorize('update', $user);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'sometimes|string|min:8',
            'is_admin' => 'sometimes|boolean',
            'profile_photo_path' => 'sometimes|url|max:2048', // Add validation for profile photo URL
        ]);



        // Hash the password if provided
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json($user);
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {


        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    /**
     * Get stories for a specific user
     * 
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function getUserStories(User $user)
    {
        $stories = $user->stories()->with('comments')->get();
        return response()->json($stories);
    }

    /**
     * Get comments for a specific user
     * 
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function getUserComments(User $user)
    {
        $comments = $user->comments()->with('story')->get();
        return response()->json($comments);
    }

    /**
     * Update the authenticated user's profile photo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $user = User::find(Auth::id());

        if ($request->hasFile('profile_photo')) {
            // Delete old photo if it exists
            if ($user && $user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $file = $request->file('profile_photo');
            $filename = 'profile-photo-' . $user->id . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile-photos', $filename, 'public');

            if ($user) {
                $user->profile_photo_path = $path;
                $user->save();
            }
        }

        return response()->json([
            'message' => 'Profile photo updated successfully',
            'profile_photo_url' => $user->profile_photo_path ? Storage::url($user->profile_photo_path) : null
        ]);
    }
}
