@extends('layouts.app')

@section('content')
    <div class="category-posts">
        <h1>{{ $category->name }}</h1>
        
        <div class="posts-grid">
            @forelse($posts as $post)
                @include('posts.partials.post-card', ['post' => $post])
            @empty
                <p>No posts in this category yet.</p>
            @endforelse
        </div>

        {{ $posts->links() }}
    </div>
@endsection