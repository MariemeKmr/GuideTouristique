<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Guide Touristique')</title>
    @include('partials.head-assets')
</head>
<body class="min-h-screen flex flex-col text-nuit antialiased">
    <div class="relative z-10 flex flex-1 flex-col items-center justify-center px-4 py-12">
        <a href="{{ route('home') }}" class="mb-6 text-2xl font-semibold text-nuit">
            Guide Touristique
        </a>

        <div class="w-full max-w-md bg-white rounded-xl shadow-sm border border-sable-200 p-8">
            @yield('content')
        </div>
    </div>

    @include('partials.footer')
</body>
</html>
