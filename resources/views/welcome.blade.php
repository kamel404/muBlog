@extends('layouts.app')

@section('title', 'Welcome to ' . config('app.name'))

@section('content')
<div class="welcome-page">
    <div class="hero-section">
        <h1>Welcome to {{ config('app.name') }}</h1>
        <p class="lead">Connect, Share, and Engage with the University Community</p>

        @guest
            <div class="cta-buttons">
                <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                <a href="{{ route('login') }}" class="btn btn-secondary">Login</a>
            </div>
        @else
            <div class="cta-buttons">
                <a href="{{ route('posts.index') }}" class="btn btn-primary">View Posts</a>
            </div>
        @endguest
    </div>
</div>
@endsection
