<x-layouts.public :title="$page->title" :meta_description="$page->meta_description">
    <article class="max-w-3xl mx-auto">
        <h1 class="text-4xl font-bold mb-6">{{ $page->title }}</h1>
        @if($page->field('cover_image'))
            <img 
                src="{{ Storage::url($page->field('cover_image')) }}" 
                alt="{{ $page->title }}" 
                class="w-full rounded-lg mb-8"
            >
        @endif
        @if($page->field('content'))
            <div class="prose max-w-none">
                {!! $page->field('content') !!}
            </div>
        @endif
    </article>
</x-layouts.public>
