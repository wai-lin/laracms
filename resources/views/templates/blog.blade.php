<x-layouts.waffle :title="$page->title" :meta_description="$page->meta_description">
    <article class="max-w-3xl mx-auto">
        {{-- Back link --}}
        <a href="{{ route('blogs') }}" class="inline-flex items-center gap-2 text-gray-600 font-semibold hover:text-amber-600 transition mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back to all posts
        </a>

        {{-- Header --}}
        <header class="mb-10">
            <div class="flex items-center gap-3 mb-4">
                <span class="px-3 py-1 bg-amber-100 text-amber-800 text-sm font-semibold rounded-full border-2 border-amber-300">
                    Blog
                </span>
                <span class="text-gray-500">
                    {{ $page->published_at->format('F d, Y') }}
                </span>
            </div>
            <h1 class="text-4xl lg:text-5xl font-black text-gray-800 leading-tight mb-4">
                {{ $page->title }}
            </h1>
            @if($page->meta_description)
                <p class="text-xl text-gray-600">
                    {{ $page->meta_description }}
                </p>
            @endif
        </header>

        {{-- Featured Image --}}
        @if($page->field('cover_image'))
            <div class="mb-10 rounded-2xl border-3 border-gray-800 waffle-shadow overflow-hidden">
                <img 
                    src="{{ Storage::url($page->field('cover_image')) }}" 
                    alt="{{ $page->title }}"
                    class="w-full"
                >
            </div>
        @endif

        {{-- Content --}}
        @if($page->field('content'))
            <div class="waffle-prose max-w-none">
                {!! $page->field('content') !!}
            </div>
        @endif

        {{-- Divider --}}
        <div class="border-t-3 border-gray-800 my-12"></div>

        {{-- Back to blogs CTA --}}
        <div class="bg-white p-8 rounded-2xl border-3 border-gray-800 waffle-shadow text-center">
            <h3 class="text-xl font-bold text-gray-800 mb-2">Hungry for more?</h3>
            <p class="text-gray-600 mb-4">Check out our other crispy thoughts and ideas.</p>
            <a href="{{ route('blogs') }}" class="inline-block px-6 py-3 bg-amber-400 text-gray-800 font-bold rounded-lg border-3 border-gray-800 waffle-shadow transition-all">
                View All Posts
            </a>
        </div>
    </article>
</x-layouts.waffle>
