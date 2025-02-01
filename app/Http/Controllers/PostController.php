<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $posts = Post::with(['user:id,name,username', 'likes', 'comments'])
                     ->latest()
                     ->paginate(9);

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'category_id' => 'required|exists:categories,id'
        ]);

        $post = new Post($request->only(['title', 'body', 'category_id']));
        $post->user_id = Auth::id();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $post->image = $path;
        }

        $post->save();

        return redirect()->route('posts.show', $post)
                        ->with('success', 'Post created successfully');
    }

    public function show(Post $post)
    {
        $post->load(['user', 'comments.user', 'likes', 'category']);
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $this->authorize('update-post', $post);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update-post', $post);

        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'category_id' => 'required|exists:categories,id'
        ]);

        $post->fill($request->only(['title', 'body']));

        if ($request->hasFile('image')) {
            // Delete old image
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $path = $request->file('image')->store('posts', 'public');
            $post->image = $path;
        }

        $post->save();

        return redirect()->route('posts.show', $post)
                        ->with('success', 'Post updated successfully');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete-post', $post);

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()->route('posts.index')
                        ->with('success', 'Post deleted successfully');
    }

    public function userPosts()
    {
        $posts = Post::where('user_id', Auth::id())
                     ->with(['user', 'likes', 'comments'])
                     ->latest()
                     ->get();

        return view('posts.my-posts', compact('posts'));
    }
}
