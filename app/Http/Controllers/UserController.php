<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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

    // Update User (for user and admin)
    public function update(Request $request)
    {

        if (!Auth::check()) {
            Log::error('User is not authenticated.');
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->has('name')) {
            $user->name = $request->input('name');
        }

        if ($request->has('email')) {
            $user->email = $request->input('email');
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role_id' => 'integer|exists:roles,id',
        ]);

        $user = User::findOrFail($id);
        $user->update($request->only(['role_id']));

        return response()->json([
            'message' => 'User role updated successfully!',
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
