@props(['title' => null, 'meta_description' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title . ' - The Waffle Studio' : 'The Waffle Studio' }}</title>
    @if($meta_description)
        <meta name="description" content="{{ $meta_description }}">
    @endif
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,600,800,900" rel="stylesheet" />
    @vite(['resources/css/waffle.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-amber-50 waffle-pattern waffle-body">
    {{-- Navigation --}}
    <nav class="flex items-center justify-between px-6 lg:px-12 py-6">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <div class="w-10 h-10 bg-amber-400 rounded-lg border-3 border-gray-800 flex items-center justify-center waffle-shadow transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <rect x="3" y="3" width="7" height="7" rx="1"/>
                    <rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                    <rect x="14" y="14" width="7" height="7" rx="1"/>
                </svg>
            </div>
            <span class="text-xl font-extrabold text-gray-800 tracking-tight">thewaffle.studio</span>
        </a>
        
        <div class="flex items-center gap-4">
            <a href="{{ route('blogs') }}" class="px-5 py-2 bg-amber-400 text-gray-800 font-bold rounded-lg border-3 border-gray-800 waffle-shadow transition-all">
                Blogs
            </a>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="px-6 lg:px-12 py-12 lg:py-16">
        <div class="max-w-5xl mx-auto">
            {{ $slot }}
        </div>
    </main>

    {{-- Footer --}}
    <footer class="px-6 lg:px-12 py-8 border-t-3 border-gray-800 mt-12">
        <div class="max-w-5xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-gray-600 font-medium">
                &copy; {{ date('Y') }} The Waffle Studio. Made with batter & love.
            </p>
            <div class="flex items-center gap-6">
                <a href="#" class="text-gray-600 hover:text-amber-600 font-semibold transition">Twitter</a>
                <a href="#" class="text-gray-600 hover:text-amber-600 font-semibold transition">Instagram</a>
                <a href="#" class="text-gray-600 hover:text-amber-600 font-semibold transition">Dribbble</a>
            </div>
        </div>
    </footer>
</body>
</html>
