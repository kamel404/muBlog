<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class ManageController extends Controller
{
    public function __construct()
    {
        // Ensure only authenticated users can access these methods
        $this->middleware('auth');
    }

    /**
     * Display the management dashboard.
     */
    public function index()
    {
        $user = auth()->user();

        // Fetch posts for the logged-in user
        $posts = $user->posts;

        // Initialize variables for admin-only content
        $categories = [];
        $users = [];

        // If the user is an admin, fetch categories and users
        if ($user->isAdmin()) {
            $categories = Category::all();
            $users = User::all();
        }

        return view('manage.index', compact('posts', 'categories', 'users'));
    }

    /**
     * Update a user's role (admin only).
     */
/**
 * Update a user's role (admin only).
 */
    public function updateUserRole(Request $request, $id)
    {
        // Ensure only admins can update roles
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('manage')->with('error', 'You do not have permission to perform this action.');
        }

        $request->validate([
            'role_id' => 'integer|exists:roles,id',
        ]);

        $user = User::findOrFail($id);
        $user->update($request->only(['role_id']));

        return redirect()->back()
            ->with('success', 'User role updated successfully');
    }

    /**
     * Delete a user (admin only).
     */
    public function deleteUser(User $user)
    {
        // Ensure only admins can delete users
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('manage')->with('error', 'You do not have permission to perform this action.');
        }

        $user->delete();

        return redirect()->route('manage')->with('success', 'User deleted successfully.');
    }

    /**
     * Delete a category (admin only).
     */
    public function deleteCategory(Category $category)
    {
        // Ensure only admins can delete categories
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('manage')->with('error', 'You do not have permission to perform this action.');
        }

        $category->delete();

        return redirect()->route('manage')->with('success', 'Category deleted successfully.');
    }

    /**
     * Delete a post.
     */
    public function deletePost(Post $post)
    {
        // Ensure only the post owner can delete the post
        if ($post->user_id !== auth()->id()) {
            return redirect()->route('manage')->with('error', 'You do not have permission to perform this action.');
        }

        $post->delete();

        return redirect()->route('manage')->with('success', 'Post deleted successfully.');
    }

    // Create a new category
    public function createCategory(Request $request)
    {
        // Ensure only admins can create categories
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('manage')->with('error', 'You do not have permission to perform this action.');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($validated);

        return redirect()->route('manage')->with('success', 'Category created successfully.');
    }

    // Update a category
    public function updateCategory(Request $request, Category $category)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('manage')
                ->with('error', 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name'])
        ];

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            // Store new image
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        return redirect()->route('manage')
            ->with('success', 'Category updated successfully.');
    }
}
