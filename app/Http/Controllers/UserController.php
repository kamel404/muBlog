<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Get all users (admin access only).
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    /**
     * Show a specific user by ID.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Update a user (e.g., promote to admin).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'string|max:255',
            'username' => 'string|unique:users,username|max:255',
            'email' => 'string|email|unique:users',
            'role_id' => 'integer|exists:roles,id',
        ]);

        $user = User::findOrFail($id);
        $user->update($request->only(['name', 'username', 'email', 'role_id']));

        return response()->json([
            'message' => 'User updated successfully!',
            'user' => $user,
        ]);
    }

    /**
     * Delete a user (admin access only).
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully!',
        ]);
    }
}
