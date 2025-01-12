<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Comment;

class CommentController extends Controller
{
    public function index($postId)
    {
        $post = Post::findOrFail($postId);

        $comments = $post->comments()->with('user')->latest()->get();

        return response()->json(['comments' => $comments], 200);
    }

    public function store(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);

        $validated = $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $comment = $post->comments()->create([
            'body' => $validated['body'],
            'user_id' => Auth::id(),
        ]);

        return response()->json(['comment' => $comment, 'message' => 'Comment added successfully'], 201);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $comment->update($validated);

        return response()->json(['comment' => $comment, 'message' => 'Comment updated successfully'], 200);
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }

}
