<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user:id,name,username', 'likes', 'comments'])
                     ->latest()
                     ->get();
                     
        return response()->json($posts);
    }

    public function show($id)
    {
        $post = Post::with(['user:id,name,username', 'likes', 'comments.user:id,name,username'])
                    ->findOrFail($id);

        // Check if user owns the post or is admin
        if (auth()->id() !== $post->user_id && !auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($post);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['title', 'body']);
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/posts'), $imageName);
            $data['image'] = 'images/posts/' . $imageName;
        }

        $data['user_id'] = $request->user()->id;
        $post = Post::create($data);

        return response()->json($post, 201);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_image' => 'nullable|boolean'
        ]);

        $data = $request->only(['title', 'body']);

        // Handle image update
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($post->image && file_exists(public_path($post->image))) {
                unlink(public_path($post->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/posts'), $imageName);
            $data['image'] = 'images/posts/' . $imageName;
        }
        
        // Handle image removal
        if ($request->input('remove_image') && $post->image) {
            if (file_exists(public_path($post->image))) {
                unlink(public_path($post->image));
            }
            $data['image'] = null;
        }

        $post->update($data);
        return response()->json($post);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== Auth::id() && !Auth::user()->hasRole('Admin')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        // Delete the associated image if it exists
        if ($post->image && file_exists(public_path($post->image))) {
            unlink(public_path($post->image));
        }

        // Delete associated likes
        $post->likes()->delete();

        // Delete associated comments
        $post->comments()->delete();

        // Finally delete the post
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }

    public function userPosts(Request $request)
    {
        $posts = Post::where('user_id', $request->user()->id)
                     ->with(['user:id,name,username', 'likes', 'comments'])
                     ->latest()
                     ->get();
                     
        return response()->json($posts);
    }
}
