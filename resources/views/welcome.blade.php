<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guide Touristique</title>
    @include('partials.head-assets')
</head>
<body class="min-h-screen flex flex-col text-nuit antialiased">
    <div class="relative z-10 flex flex-col flex-1 overflow-hidden">
        {{-- Formes décoratives floutées (profondeur) --}}
        <div class="pointer-events-none absolute -top-32 -right-24 h-96 w-96 rounded-full bg-lagon/20 blur-3xl"></div>
        <div class="pointer-events-none absolute top-1/3 -left-24 h-80 w-80 rounded-full bg-terracotta/10 blur-3xl"></div>

        {{-- En-tête --}}
        <header class="relative z-10">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
                <span class="flex items-center gap-2 text-lg font-bold tracking-tight text-nuit">
                    <span class="grid h-8 w-8 place-items-center rounded-xl bg-lagon text-sm text-white">GT</span>
                    Guide Touristique
                </span>
                <nav class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="rounded-full bg-terracotta px-5 py-2 text-sm font-semibold text-white shadow-soft hover:bg-terracotta-600 hover:shadow-lift transition">
                            Mon espace
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="rounded-full px-4 py-2 text-sm font-medium text-nuit/80 hover:text-nuit transition">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}"
                           class="rounded-full border border-lagon bg-white/60 px-4 py-2 text-sm font-semibold text-lagon-700 backdrop-blur hover:bg-lagon-50 transition">
                            Inscription
                        </a>
                    @endauth
                </nav>
            </div>
        </header>

        {{-- Héro --}}
        <main class="relative z-10 flex-1 flex items-center">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center py-20 sm:py-28">
                <span class="inline-flex items-center gap-2 rounded-full border border-sable-300 bg-white/60 px-4 py-1.5 text-xs font-medium text-lagon-700 backdrop-blur">
                    <span class="h-1.5 w-1.5 rounded-full bg-terracotta"></span>
                    Votre évasion commence ici
                </span>

                <h1 class="mt-6 text-4xl sm:text-6xl font-extrabold tracking-tight text-nuit leading-[1.05]">
                    Explorez les destinations,<br class="hidden sm:block"> en toute simplicité.
                </h1>

                <p class="mt-6 text-lg text-nuit/70 max-w-2xl mx-auto">
                    Découvrez des lieux à visiter, planifiez vos déplacements et entrez
                    en contact avec des chauffeurs de confiance.
                </p>

                @guest
                    <div class="mt-9 flex flex-wrap items-center justify-center gap-3">
                        <a href="{{ route('register') }}"
                           class="rounded-full bg-terracotta px-7 py-3.5 text-sm font-semibold text-white shadow-soft hover:bg-terracotta-600 hover:shadow-lift transition">
                            Découvrir
                        </a>
                        <a href="{{ route('login') }}"
                           class="rounded-full border border-lagon bg-white/60 px-7 py-3.5 text-sm font-semibold text-lagon-700 backdrop-blur hover:bg-lagon-50 transition">
                            J'ai déjà un compte
                        </a>
                    </div>
                @endguest

                {{-- Atouts --}}
                <div class="mt-16 grid gap-4 sm:grid-cols-3 text-left">
                    <div class="rounded-2xl border border-sable-200 bg-white/70 p-5 shadow-soft backdrop-blur">
                        <h3 class="font-semibold text-nuit">Destinations</h3>
                        <p class="mt-1 text-sm text-nuit/60">Parcourez les lieux et gardez la trace de vos visites.</p>
                    </div>
                    <div class="rounded-2xl border border-sable-200 bg-white/70 p-5 shadow-soft backdrop-blur">
                        <h3 class="font-semibold text-nuit">Transports</h3>
                        <p class="mt-1 text-sm text-nuit/60">Comparez les moyens de déplacement et leurs coûts.</p>
                    </div>
                    <div class="rounded-2xl border border-sable-200 bg-white/70 p-5 shadow-soft backdrop-blur">
                        <h3 class="font-semibold text-nuit">Chauffeurs</h3>
                        <p class="mt-1 text-sm text-nuit/60">Trouvez un chauffeur de confiance et contactez-le.</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @include('partials.footer')
</body>
</html>
