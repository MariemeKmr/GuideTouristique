<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guide Touristique</title>
    @include('partials.head-assets')
</head>
<body class="min-h-screen flex flex-col text-nuit antialiased">
    <div class="relative z-10 flex flex-col flex-1">
        {{-- En-tête (secondaire : lagon) --}}
        <header class="bg-lagon text-white">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
                <span class="text-lg font-semibold">Guide Touristique</span>
                <nav class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="rounded-md bg-terracotta px-4 py-2 text-sm font-medium text-white hover:bg-terracotta-600 transition">
                            Mon espace
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="rounded-md px-4 py-2 text-sm font-medium text-white/90 hover:bg-white/10 transition">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}"
                           class="rounded-md bg-terracotta px-4 py-2 text-sm font-medium text-white hover:bg-terracotta-600 transition">
                            Inscription
                        </a>
                    @endauth
                </nav>
            </div>
        </header>

        {{-- Section héro --}}
        <main class="flex-1 flex items-center">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center py-24">
                <h1 class="text-3xl sm:text-5xl font-semibold text-nuit leading-tight">
                    Explorez les destinations, simplement.
                </h1>
                <p class="mt-5 text-base sm:text-lg text-nuit/70">
                    Découvrez des lieux à visiter, planifiez vos déplacements
                    et entrez en contact avec des chauffeurs de confiance.
                </p>

                @guest
                    <div class="mt-8 flex items-center justify-center gap-3">
                        <a href="{{ route('register') }}"
                           class="rounded-md bg-terracotta px-6 py-3 text-sm font-medium text-white hover:bg-terracotta-600 transition shadow-sm">
                            Découvrir
                        </a>
                        <a href="{{ route('login') }}"
                           class="rounded-md border border-lagon px-6 py-3 text-sm font-medium text-lagon hover:bg-lagon-50 transition">
                            J'ai déjà un compte
                        </a>
                    </div>
                @endguest
            </div>
        </main>
    </div>

    @include('partials.footer')
</body>
</html>
