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
        $comments = Comment::where('post_id', $postId)
                          ->with('user')
                          ->orderBy('created_at', 'desc')
                          ->get();
                          
        return response()->json($comments);
    }

    public function store(Request $request, $postId)
    {
        $request->validate([
            'body' => 'required|string'
        ]);

        $comment = Comment::create([
            'body' => $request->body,
            'user_id' => $request->user()->id,
            'post_id' => $postId
        ]);

        // Load the user relationship for the response
        $comment->load('user');

        return response()->json($comment, 201);
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
