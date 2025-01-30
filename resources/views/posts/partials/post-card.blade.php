<div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 group overflow-hidden">
    @if($post->image)
    <div class="relative h-48 overflow-hidden">
        <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" 
             class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
    </div>
    @endif

    <div class="p-6 space-y-4">
        <div class="space-y-2">
            <h2 class="text-2xl font-bold text-gray-800 group-hover:text-blue-600 transition-colors">
                <a href="{{ route('posts.show', $post) }}" class="hover:underline">
                    {{ $post->title }}
                </a>
            </h2>
            <div class="flex items-center text-sm text-gray-500 space-x-3">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>{{ $post->user->name }}</span>
                </div>
                <span>•</span>
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ $post->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        <div class="text-gray-600 leading-relaxed">
            {{ Str::limit($post->body, 200) }}
        </div>

        <div class="flex items-center justify-between border-t border-gray-100 pt-4">
            <div class="flex items-center space-x-4 text-gray-500">
                <div class="flex items-center space-x-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                    <span>{{ $post->comments->count() }}</span>
                </div>
                <div class="flex items-center space-x-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span>{{ $post->likes->count() }}</span>
                </div>
            </div>

            @auth
            <form action="{{ route('posts.like', $post) }}" method="POST">
                @csrf
                <button type="submit" 
                        class="flex items-center px-4 py-2 bg-gradient-to-r from-blue-100 to-indigo-100 hover:from-blue-200 hover:to-indigo-200 text-blue-600 rounded-full transition-all 
                               {{ $post->userLikes->count() ? 'bg-blue-100' : '' }}">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span>{{ $post->userLikes->count() ? 'Liked' : 'Like' }}</span>
                </button>
            </form>
            @endauth
        </div>
    </div>
</div>