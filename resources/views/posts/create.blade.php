@extends('layouts.app')

@section('title', 'Create Post')

@section('content')
<div class="post-form">
    <h1>Create New Post</h1>

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required>
        </div>

        <div class="form-group">
            <label for="body">Content</label>
            <textarea name="body" id="body" rows="5" required>{{ old('body') }}</textarea>
        </div>

        <div class="form-group">
            <label for="image">Image (optional)</label>
            <input type="file" name="image" id="image" accept="image/*">
        </div>

        <button type="submit" class="btn">Create Post</button>
    </form>
</div>
@endsection
