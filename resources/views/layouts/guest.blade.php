<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Guide Touristique')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 text-gray-800 antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-10">
        <a href="{{ route('home') }}" class="mb-6 text-2xl font-semibold text-gray-900">
            Guide Touristique
        </a>

        <div class="w-full max-w-md bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            @yield('content')
        </div>
    </div>
</body>
</html>
