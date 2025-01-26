
@extends('layouts.app')

@section('title', 'My Posts')

@section('content')
<div class="my-posts-header">
    <h1>My Posts</h1>
    <a href="{{ route('posts.create') }}" class="btn">Create New Post</a>
</div>

<div class="posts-container">
    @if($posts->isEmpty())
        <p>You haven't created any posts yet.</p>
    @else
        @foreach($posts as $post)
            @include('posts.partials.post-card', ['post' => $post])
        @endforeach
    @endif
</div>
@endsection
