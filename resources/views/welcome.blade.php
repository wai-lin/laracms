<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-zinc-50 flex flex-col items-center justify-center p-6">
    <main class="text-center">
        <h1 class="text-4xl font-bold text-zinc-900 mb-4">{{ config('app.name') }}</h1>
        <p class="text-zinc-600 mb-8">A simple, developer-friendly CMS</p>
        
        <div class="flex gap-4 justify-center">
            @auth
                <a href="{{ route('dashboard') }}" class="px-6 py-2 bg-zinc-900 text-white rounded-lg hover:bg-zinc-800 transition">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="px-6 py-2 bg-zinc-900 text-white rounded-lg hover:bg-zinc-800 transition">
                    Log in
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="px-6 py-2 border border-zinc-300 text-zinc-700 rounded-lg hover:border-zinc-400 transition">
                        Register
                    </a>
                @endif
            @endauth
        </div>
    </main>
    
    <footer class="absolute bottom-6 text-zinc-400 text-sm">
        &copy; {{ date('Y') }} {{ config('app.name') }}
    </footer>
</body>
</html>