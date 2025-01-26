<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Like;

class LikeController extends Controller
{
    // Like a post
    public function store($postId)
    {
        $post = Post::findOrFail($postId);

        $like = Like::firstOrCreate([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
        ]);

        return response()->json(['message' => 'Post liked successfully!', 'like' => $like], 201);
    }


    // Unlike a post
    public function destroy($postId)
    {
        $like = Like::where('user_id', Auth::id())
                    ->where('post_id', $postId)
                    ->first();

        if (!$like) {
            return response()->json(['message' => 'Like not found'], 404);
        }

        $like->delete();

        return response()->json(['message' => 'Post unliked successfully'], 200);
    }

    // Get number of likes for a post
    public function count($postId)
    {
        $post = Post::findOrFail($postId);

        $likesCount = $post->likes()->count();

        return response()->json(['likes_count' => $likesCount], 200);
    }

    public function toggle(Request $request, Post $post)
    {
        $user = $request->user();

        $existingLike = $post->likes()
                            ->where('user_id', $user->id)
                            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $message = 'Post unliked successfully';
        } else {
            $post->likes()->create([
                'user_id' => $user->id
            ]);
            $message = 'Post liked successfully';
        }

        return redirect()->back()
                        ->with('success', $message);
    }

}
