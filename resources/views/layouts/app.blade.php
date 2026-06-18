<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Guide Touristique')</title>
    @include('partials.head-assets')
</head>
<body class="min-h-screen flex flex-col text-nuit antialiased">
    <div class="relative z-10 flex flex-col min-h-screen">
        <div class="flex-1">
            @yield('content')
        </div>
        @include('partials.footer')
    </div>
</body>
</html>
