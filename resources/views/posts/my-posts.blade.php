@extends('layouts.app')

@section('title', 'My Posts')

@section('content')
<section class="py-16 bg-gradient-to-br from-blue-50 to-indigo-50 animate-fade-in-up">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 space-y-4 md:space-y-0">
                <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent animate-fade-in-down">
                    My Posts
                </h1>
                <a href="{{ route('posts.create') }}"
                   class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-2 px-6 rounded-lg font-semibold
                          hover:from-blue-700 hover:to-indigo-700 transition-all transform hover:scale-105 inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Create New Post
                </a>
            </div>

            <!-- Posts Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($posts as $post)
                    @include('posts.partials.post-card', ['post' => $post])
                @endforeach
            </div>

            <!-- Empty State -->
            @if($posts->isEmpty())
                <div class="text-center py-12 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    You haven't created any posts yet.
                </div>
            @endif

            <!-- Pagination -->
            <div class="mt-12 animate-fade-in-up">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</section>
@endsection
