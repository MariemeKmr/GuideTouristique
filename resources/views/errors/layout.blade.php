{{-- Layout partage des pages d'erreur. Autonome : pas de navbar ni de requete base de donnees,
     pour rester affichable meme lorsque l'application est en panne (erreur 500). --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('code') · Guide Touristique</title>
    @include('partials.head-assets')
</head>
<body class="min-h-screen flex flex-col text-nuit antialiased">
    <div class="relative z-10 flex flex-col flex-1 overflow-hidden">
        {{-- Halos decoratifs (identiques a la page d'accueil) --}}
        <div class="pointer-events-none absolute -top-32 -right-24 h-96 w-96 rounded-full bg-lagon/20 blur-3xl"></div>
        <div class="pointer-events-none absolute top-1/3 -left-24 h-80 w-80 rounded-full bg-terracotta/10 blur-3xl"></div>

        {{-- En-tete minimal : logo cliquable vers l'accueil --}}
        <header class="relative z-10">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-2 text-lg font-bold tracking-tight text-nuit">
                    <span class="grid h-8 w-8 place-items-center rounded-xl bg-lagon text-sm text-white">GT</span>
                    Guide Touristique
                </a>
            </div>
        </header>

        {{-- Contenu --}}
        <main class="relative z-10 flex-1 flex items-center">
            <div class="max-w-xl mx-auto px-4 sm:px-6 text-center py-16 sm:py-24">
                <span class="inline-flex items-center gap-2 rounded-full border border-sable-300 bg-white/60 px-4 py-1.5 text-xs font-medium text-lagon-700 backdrop-blur">
                    <span class="h-1.5 w-1.5 rounded-full bg-terracotta"></span>
                    @yield('badge', 'Une pause sur la route')
                </span>

                <p class="mt-6 text-7xl sm:text-8xl font-extrabold tracking-tight text-nuit leading-none">@yield('code')</p>

                <h1 class="mt-4 text-2xl sm:text-3xl font-bold tracking-tight text-nuit">@yield('titre')</h1>

                <p class="mt-4 text-lg text-nuit/70 max-w-md mx-auto">@yield('message')</p>

                <div class="mt-9 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ url('/') }}"
                       class="rounded-full bg-terracotta px-7 py-3.5 text-sm font-semibold text-white shadow-soft hover:bg-terracotta-600 hover:shadow-lift transition">
                        Retour à l'accueil
                    </a>
                    @yield('action')
                </div>
            </div>
        </main>

        {{-- Pied de page minimal --}}
        <footer class="relative z-10 py-6 text-center text-xs text-nuit/50">
            Guide Touristique · Découvrez le Sénégal
        </footer>
    </div>
</body>
</html>
