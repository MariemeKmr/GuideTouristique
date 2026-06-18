<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Guide Touristique')</title>
    @include('partials.head-assets')
</head>
<body class="min-h-screen flex flex-col text-nuit antialiased">
    <div class="relative z-10 flex flex-1 flex-col items-center justify-center px-4 py-12 overflow-hidden">
        <div class="pointer-events-none absolute -top-24 -right-16 h-80 w-80 rounded-full bg-lagon/20 blur-3xl"></div>

        <a href="{{ route('home') }}" class="relative z-10 mb-6 flex items-center gap-2 text-2xl font-bold text-nuit">
            <span class="grid h-9 w-9 place-items-center rounded-xl bg-lagon text-sm text-white">GT</span>
            Guide Touristique
        </a>

        <div class="relative z-10 w-full max-w-md bg-white rounded-3xl shadow-lift border border-sable-200 p-8">
            @yield('content')
        </div>
    </div>

    @include('partials.footer')
</body>
</html>
