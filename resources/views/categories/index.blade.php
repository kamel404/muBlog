@extends('layouts.app')

@section('content')
    <div class="categories-container">
        <h1>Categories</h1>
        <div class="category-grid">
            @foreach($categories as $category)
                <div class="category-card">
                    @if($category->image)
                        <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}">
                    @endif
                    <h2>{{ $category->name }}</h2>
                    <a href="{{ route('category.show', $category) }}" class="btn">View Posts</a>
                </div>
            @endforeach
        </div>
    </div>
@endsection