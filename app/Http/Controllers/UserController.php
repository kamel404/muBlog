<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|unique:users,username|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $defaultRoleId = 2; // Assign the "User" role directly by its ID

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username ?? 'NewUser',
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $defaultRoleId,
        ]);

        return response()->json($user, 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user]);
    }

}
