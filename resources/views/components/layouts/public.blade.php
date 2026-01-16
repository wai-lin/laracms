@props(['title' => null, 'meta_description' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    @if ($meta_description)
        <meta name="description" content="{{ $meta_description }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white">
    <header class="border-b border-zinc-200 py-4">
        <nav class="max-w-5xl mx-auto px-4">
            <a href="/" class="font-semibold text-lg">{{ config('app.name') }}</a>
            {{-- Developer adds navigation links here --}}
        </nav>
    </header>
    <main class="max-w-5xl mx-auto px-4 py-8">
        {{ $slot }}
    </main>
    <footer class="border-t border-zinc-200 py-6 mt-auto">
        <div class="max-w-5xl mx-auto px-4 text-center text-zinc-500 text-sm">
            &copy; {{ date('Y') }} {{ config('app.name') }}
        </div>
    </footer>
</body>
</html>
