
@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="posts-container" id="postsContainer">
    <!-- Posts will be loaded here -->
</div>
@endsection

@push('scripts')
<script>
    const posts = @json($posts);
    document.addEventListener('DOMContentLoaded', () => {
        displayPosts(posts);
    });
</script>
<script src="{{ asset('js/main.js') }}"></script>
@endpush
