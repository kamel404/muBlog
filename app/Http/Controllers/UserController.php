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

    public function profile(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'role_id' => $user->role_id
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email,'.$request->user()->id,
                'regex:/^[a-zA-Z0-9._%+-]+@mu\.edu\.lb$/' // Ensure email is from mu.edu.lb
            ],
        ], [
            'email.regex' => 'The email must be a valid MU email address (@mu.edu.lb)'
        ]);

        $user = $request->user();
        $data = $request->only(['name', 'email']);
        
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
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
