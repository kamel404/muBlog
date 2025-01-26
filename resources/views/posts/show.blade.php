
@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="post-detail">
    <h1>{{ $post->title }}</h1>
    @if($post->image)
        <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}">
    @endif
    <div class="post-content">
        {{ $post->body }}
    </div>

    @can('update', $post)
    <div class="actions">
        <a href="{{ route('posts.edit', $post) }}" class="btn">Edit</a>
        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
        </form>
    </div>
    @endcan

    <div class="comments-section">
        @foreach($post->comments as $comment)
            @include('posts.partials.comment', ['comment' => $comment])
        @endforeach

        @auth
        <form action="{{ route('comments.store', $post) }}" method="POST">
            @csrf
            <textarea name="body" required></textarea>
            <button type="submit" class="btn">Comment</button>
        </form>
        @endauth
    </div>
</div>
@endsection
