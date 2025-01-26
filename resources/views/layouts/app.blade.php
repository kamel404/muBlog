<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('styles')
</head>
<body>
    <header>
        <nav>
            <div class="nav-left">
                <a href="{{ route('home') }}" class="logo">{{ config('app.name') }}</a>
            </div>
            <div class="nav-right">
                @auth
                    <a href="{{ route('posts.create') }}" class="btn">Create Post</a>
                    <a href="{{ route('posts.my') }}" class="btn">My Posts</a>
                    <a href="{{ route('profile.edit') }}" class="btn">Profile</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="btn">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn">Login</a>
                    <a href="{{ route('register') }}" class="btn">Register</a>
                @endauth
            </div>
        </nav>
    </header>

    <main>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </footer>

    @stack('scripts')
</body>
</html>
