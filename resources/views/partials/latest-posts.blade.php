<section class="py-16 bg-gradient-to-br from-blue-50 to-indigo-50 animate-fade-in-up">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent mb-8 text-center animate-fade-in-down">
                Latest Community Posts
            </h2>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($latestPosts as $post)
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 group overflow-hidden">
                        <div class="p-6 space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="bg-blue-100 p-2 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">
                                    {{ $post->title }}
                                </h3>
                            </div>
                            
                            <div class="flex items-center text-gray-500">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-sm">{{ $post->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-100 px-6 py-3 bg-gray-50">
                            <a href="{{ route('posts.show', $post) }}" 
                               class="text-blue-600 hover:text-indigo-600 font-medium inline-flex items-center">
                                Read More
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($latestPosts->isEmpty())
                <div class="text-center py-12 text-gray-500">
                    No posts available yet. Be the first to share!
                </div>
            @endif
        </div>
    </div>
</section>