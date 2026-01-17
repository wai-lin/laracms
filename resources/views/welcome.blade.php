@php
    $page = \App\Models\Page::bySlugOrFail('where-ideas-get-crispy');
@endphp

<x-layouts.waffle :title="$page->title" :meta_description="$page->meta_description">
    {{-- Hero Section --}}
    <div class="text-center mb-16">
        <h1 class="text-5xl lg:text-7xl font-black text-gray-800 mb-6 leading-tight">
            {{ $page->field('hero_title') }}<br>
            <span class="squiggle">{{ $page->field('hero_title_crispy') }}</span>
        </h1>
        <p class="text-xl lg:text-2xl text-gray-600 mb-10 max-w-2xl mx-auto">
            {{ $page->field('hero_description') }}
        </p>
    </div>

    {{-- Services Grid --}}
    <div id="work" class="grid md:grid-cols-3 gap-6 mb-20">
        <div class="bg-white p-8 rounded-2xl border-3 border-gray-800 waffle-shadow transition-all">
            <div class="w-14 h-14 bg-pink-300 rounded-xl border-3 border-gray-800 flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">
                {{ $page->field('service_one_title') }}
            </h3>
            <p class="text-gray-600">{{ $page->field('service_one_description') }}</p>
        </div>
        
        <div class="bg-white p-8 rounded-2xl border-3 border-gray-800 waffle-shadow transition-all">
            <div class="w-14 h-14 bg-cyan-300 rounded-xl border-3 border-gray-800 flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $page->field('service_two_title') }}</h3>
            <p class="text-gray-600">{{ $page->field('service_two_description') }}</p>
        </div>
        
        <div class="bg-white p-8 rounded-2xl border-3 border-gray-800 waffle-shadow transition-all">
            <div class="w-14 h-14 bg-lime-300 rounded-xl border-3 border-gray-800 flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $page->field('service_three_title') }}</h3>
            <p class="text-gray-600">{{ $page->field('service_three_description') }}</p>
        </div>
    </div>

    {{-- CTA Section (hidden for now)
    <div id="contact" class="bg-amber-400 p-10 lg:p-16 rounded-3xl border-3 border-gray-800 waffle-shadow text-center">
        <h2 class="text-3xl lg:text-4xl font-black text-gray-800 mb-4">
            Ready to cook something up?
        </h2>
        <p class="text-lg text-gray-700 mb-8 max-w-xl mx-auto">
            Drop us a line and let's chat about your next big idea. We bring the syrup.
        </p>
        <a href="mailto:hello@thewaffle.studio" class="inline-block px-8 py-4 bg-gray-800 text-amber-50 font-bold text-lg rounded-xl border-3 border-gray-800 hover:bg-gray-700 transition-all">
            hello@thewaffle.studio
        </a>
    </div>
    --}}
</x-layouts.waffle>
