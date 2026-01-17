<x-layouts.waffle title="Blogs">
    {{-- Header --}}
    <div class="text-center mb-12">
        <h1 class="text-4xl lg:text-5xl font-black text-gray-800 mb-4">
            Fresh from the <span class="squiggle">griddle</span>
        </h1>
        <p class="text-xl text-gray-600 max-w-xl mx-auto">
            Thoughts, ideas, and crispy insights from our kitchen.
        </p>
    </div>

    {{-- Blog Posts Grid --}}
    @php
        $posts = \App\Models\Page::byTemplate('blog');
    @endphp

    @if($posts->isEmpty())
        <div class="bg-white p-12 rounded-2xl border-3 border-gray-800 waffle-shadow text-center">
            <div class="w-20 h-20 bg-amber-200 rounded-full border-3 border-gray-800 flex items-center justify-center mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">No posts yet!</h2>
            <p class="text-gray-600">The kitchen is warming up. Check back soon for fresh content.</p>
        </div>
    @else
        <div class="grid md:grid-cols-2 gap-8">
            @foreach($posts as $post)
                <a href="{{ route('page.show', $post->slug) }}" class="block bg-white rounded-2xl border-3 border-gray-800 waffle-shadow transition-all overflow-hidden group">
                    @if($post->field('cover_image'))
                        <div class="aspect-video overflow-hidden border-b-3 border-gray-800">
                            <img 
                                src="{{ Storage::url($post->field('cover_image')) }}" 
                                alt="{{ $post->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            >
                        </div>
                    @else
                        <div class="aspect-video bg-gradient-to-br from-amber-200 to-amber-300 border-b-3 border-gray-800 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="3" width="7" height="7" rx="1"/>
                                <rect x="14" y="3" width="7" height="7" rx="1"/>
                                <rect x="3" y="14" width="7" height="7" rx="1"/>
                                <rect x="14" y="14" width="7" height="7" rx="1"/>
                            </svg>
                        </div>
                    @endif
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="px-3 py-1 bg-amber-100 text-amber-800 text-sm font-semibold rounded-full border-2 border-amber-300">
                                Blog
                            </span>
                            <span class="text-gray-500 text-sm">
                                {{ $post->published_at->format('M d, Y') }}
                            </span>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-amber-600 transition-colors">
                            {{ $post->title }}
                        </h2>
                        @if($post->meta_description)
                            <p class="text-gray-600 line-clamp-2">
                                {{ $post->meta_description }}
                            </p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</x-layouts.waffle>
