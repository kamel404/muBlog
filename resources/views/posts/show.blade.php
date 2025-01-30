@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="min-h-[calc(100vh-200px)] bg-gradient-to-br from-blue-50 to-indigo-50">
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-2xl overflow-hidden animate-fade-in-down">
            <!-- Post Header -->
            <div class="px-8 py-6 border-b border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <img src="https://www.mu.edu.lb/images/logo-round.png"
                             alt="University Logo"
                             class="w-10 h-10 object-cover rounded-full">
                        <div>
                            <h2 class="text-xl font-semibold">{{ $post->user->name }}</h2>
                            <p class="text-sm text-gray-500">
                                {{ $post->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    @can('update', $post)
                    <div class="flex space-x-2">
                        <a href="{{ route('posts.edit', $post) }}"
                           class="px-4 py-2 bg-blue-100 text-blue-600 rounded-full hover:bg-blue-200 transition-colors">
                            Edit
                        </a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-4 py-2 bg-red-100 text-red-600 rounded-full hover:bg-red-200 transition-colors"
                                    onclick="return confirm('Are you sure?')">
                                Delete
                            </button>
                        </form>
                    </div>
                    @endcan
                </div>

                <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    {{ $post->title }}
                </h1>

                @if($post->category)
                <div class="mt-2 flex items-center text-sm text-blue-600">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    {{ $post->category->name }}
                </div>
                @endif
            </div>

            <!-- Post Image -->
            @if($post->image)
            <div class="relative h-96 overflow-hidden">
                <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}"
                     class="w-full h-full object-cover transform transition-transform duration-300">
            </div>
            @endif

            <!-- Post Content -->
            <div class="px-8 py-6 space-y-6">
                <div class="text-gray-600 leading-relaxed whitespace-pre-line">
                    {{ $post->body }}
                </div>

                <!-- Comments Section -->
                <div class="border-t border-gray-100 pt-6">
                    <h3 class="text-xl font-semibold mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                        {{ $post->comments->count() }} Comments
                    </h3>

                    <!-- Comments List -->
                    <div class="space-y-4">
                        @foreach($post->comments as $comment)
                            @include('posts.partials.comment', ['comment' => $comment])
                        @endforeach
                    </div>

                    <!-- Comment Form -->
                    @auth
                    <form action="{{ route('comments.store', $post) }}" method="POST" class="mt-8">
                        @csrf
                        <div class="relative">
                            <div class="absolute top-3 left-3">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                            </div>
                            <textarea name="body"
                                      class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      rows="3"
                                      placeholder="Write your comment..."
                                      required></textarea>
                            @error('body')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button type="submit"
                                    class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-2 px-6 rounded-lg font-semibold
                                           hover:from-blue-700 hover:to-indigo-700 transition-all transform hover:scale-105">
                                Post Comment
                            </button>
                        </div>
                    </form>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
