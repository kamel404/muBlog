<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => [
                'required', 'string', 'email', 'max:255', 'unique:users',
                'regex:/^[a-zA-Z0-9._%+-]+@mu\.edu\.lb$/'
            ],
            'password' => 'required|string|min:8|confirmed',
        ]);

        $adminEmail = env('ADMIN_EMAIL', '10121641@mu.edu.lb');
        $roleId = ($request->email === $adminEmail) ? 2 : 1;

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $roleId,
        ]);

        return redirect()->route('login')
                        ->with('success', 'Registration successful! Please login.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('posts.index');
        }

        return back()->withErrors([
            'email' => 'Invalid Credentials',
        ])->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
