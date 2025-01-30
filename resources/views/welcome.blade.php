@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-200px)] bg-gradient-to-br from-blue-50 to-indigo-50">
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-4xl mx-auto text-center space-y-8">
            <!-- Animated Header -->
            <div class="animate-fade-in-down">
                <h1 class="text-5xl md:text-6xl font-extrabold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent mb-6">
                    Welcome to {{ config('app.name') }}
                </h1>
                <p class="text-xl md:text-2xl text-gray-700 mb-12 font-light">
                    Your Gateway to University Connections & Shared Experiences
                </p>
            </div>

            <!-- Feature Icons Grid -->
            <div class="grid md:grid-cols-3 gap-8 mb-16 animate-fade-in-up">
                <div class="p-6 bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Connect</h3>
                    <p class="text-gray-600">Build meaningful relationships with peers and faculty</p>
                </div>

                <div class="p-6 bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Share</h3>
                    <p class="text-gray-600">Exchange ideas, resources, and campus experiences</p>
                </div>

                <div class="p-6 bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Grow</h3>
                    <p class="text-gray-600">Enhance your academic and social journey</p>
                </div>
            </div>

            <!-- Action Buttons with Icons -->
            @guest
            <div class="flex flex-col sm:flex-row justify-center gap-4 animate-slide-up">
                <a href="{{ route('register') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl text-lg font-semibold transition-all transform hover:scale-105 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Get Started
                </a>
                <a href="{{ route('login') }}" 
                   class="bg-white hover:bg-gray-50 text-blue-600 border-2 border-blue-600 px-8 py-4 rounded-xl text-lg font-semibold transition-all transform hover:scale-105 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Login
                </a>
            </div>
            @else
            <div class="animate-slide-up">
                <a href="{{ route('posts.index') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl text-lg font-semibold transition-all transform hover:scale-105 flex items-center justify-center gap-2 mx-auto">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    Explore Posts
                </a>
            </div>
            @endguest
        </div>
    </div>
</div>
@endsection