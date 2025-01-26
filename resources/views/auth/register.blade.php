@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="auth-container">
    <div class="auth-header">
        <h2>Create Account</h2>
    </div>

    <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
            @error('name')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" value="{{ old('username') }}" required>
            @error('username')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email (@mu.edu.lb)</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
            @error('email')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            @error('password')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-full">Register</button>

        <div class="auth-link">
            Already have an account? <a href="{{ route('login') }}">Login</a>
        </div>
    </form>
</div>
@endsection
