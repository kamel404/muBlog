<div class="comment" id="comment-{{ $comment->id }}">
    <div class="comment-header">
        <span class="comment-author">{{ $comment->user->name }}</span>
        <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
    </div>

    <div class="comment-body">
        {{ $comment->body }}
    </div>

    @can('delete', $comment)
        <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-delete" onclick="return confirm('Delete this comment?')">
                Delete
            </button>
        </form>
    @endcan
</div>
