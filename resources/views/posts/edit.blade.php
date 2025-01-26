@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
<div class="post-form">
    <h1>Edit Post</h1>

    <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" required>
        </div>

        <div class="form-group">
            <label for="body">Content</label>
            <textarea name="body" id="body" rows="5" required>{{ old('body', $post->body) }}</textarea>
        </div>

        <div class="form-group">
            @if($post->image)
                <div class="current-image">
                    <img src="{{ Storage::url($post->image) }}" alt="Current image">
                </div>
            @endif
            <label for="image">New Image (optional)</label>
            <input type="file" name="image" id="image" accept="image/*">
        </div>

        <button type="submit" class="btn">Update Post</button>
    </form>
</div>
@endsection
