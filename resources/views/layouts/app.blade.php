<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Guide Touristique')</title>

    {{-- Tailwind via CDN : aucun build npm requis.
         Pour passer en production avec Vite, voir le README. --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 text-gray-800 antialiased">
    @yield('content')
</body>
</html>
