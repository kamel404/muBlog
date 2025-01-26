<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Post;
use App\Models\Comment;


class CommentController extends Controller
{
    use AuthorizesRequests;
    public function index($postId)
    {
        $comments = Comment::where('post_id', $postId)
                          ->with('user')
                          ->orderBy('created_at', 'desc')
                          ->get();

        return response()->json($comments);
    }

    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $post->comments()->create([
            'body' => $validated['body'],
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Comment added successfully');
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

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully');
    }

}
