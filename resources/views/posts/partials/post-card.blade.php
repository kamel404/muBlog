<div class="post-card">
    <div class="post-header">
        <h2>
            <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
        </h2>
        <span class="post-meta">
            By {{ $post->user->name }} | {{ $post->created_at->diffForHumans() }}
        </span>
    </div>

    @if($post->image)
        <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}">
    @endif

    <div class="post-excerpt">
        {{ Str::limit($post->body, 200) }}
    </div>

    <div class="post-footer">
        <span class="comments-count">
            {{ $post->comments->count() }} comments
        </span>
        <span class="likes-count">
            {{ $post->likes->count() }} likes
        </span>
        @auth
            <form action="{{ route('posts.like', $post) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="btn-like">
                    {{ $post->userLikes->count() ? 'Unlike' : 'Like' }}
                </button>
            </form>
        @endauth
    </div>
</div>