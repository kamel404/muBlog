@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="auth-container">
    <div class="auth-header">
        <h2>Login</h2>
    </div>

    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf
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

        <button type="submit" class="btn btn-full">Login</button>

        <div class="auth-link">
            Don't have an account? <a href="{{ route('register') }}">Register</a>
        </div>
    </form>
</div>
@endsection
