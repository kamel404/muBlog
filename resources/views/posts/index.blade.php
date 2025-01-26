@extends('layouts.app')

@section('title', 'Posts')

@section('content')
<div class="posts-container">
    @foreach($posts as $post)
        @include('posts.partials.post-card', ['post' => $post])
    @endforeach
</div>
@endsection
