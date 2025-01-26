<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Get all users (admin access only).
     */
    public function index()
    {
        $users = User::all();
        return view('users.show', compact('users'));
    }

    /**
     * Show edit profile form
     */
    public function edit()
    {
        $user = Auth::user();
        return view('user.edit-profile', compact('user'));
    }

    /**
     * Show a specific user by ID.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
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
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required', 'string', 'email', 'max:255',
                'unique:users,email,'.$user->id,
                'regex:/^[a-zA-Z0-9._%+-]+@mu\.edu\.lb$/'
            ],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('profile.edit')
                        ->with('success', 'Profile updated successfully');
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role_id' => 'integer|exists:roles,id',
        ]);

        $user = User::findOrFail($id);
        $user->update($request->only(['role_id']));

        return redirect()->back()
        ->with('success', 'User role updated successfully');
    }

    /**
     * Delete a user (admin access only).
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')
                        ->with('success', 'User deleted successfully');
    }
}
