<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guide Touristique</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 text-gray-800 antialiased">
    <div class="min-h-screen flex flex-col">
        {{-- En-tête --}}
        <header class="border-b border-gray-200 bg-white">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
                <span class="text-lg font-semibold text-gray-900">Guide Touristique</span>
                <nav class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800 transition">
                            Mon espace
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="rounded-md px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}"
                           class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800 transition">
                            Inscription
                        </a>
                    @endauth
                </nav>
            </div>
        </header>

        {{-- Contenu central --}}
        <main class="flex-1 flex items-center">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center py-20">
                <h1 class="text-3xl sm:text-4xl font-semibold text-gray-900">
                    Explorez les destinations, simplement.
                </h1>
                <p class="mt-4 text-base text-gray-500">
                    Découvrez des lieux à visiter, planifiez vos déplacements
                    et entrez en contact avec des chauffeurs de confiance.
                </p>

                @guest
                    <div class="mt-8 flex items-center justify-center gap-3">
                        <a href="{{ route('register') }}"
                           class="rounded-md bg-gray-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-gray-800 transition">
                            Commencer
                        </a>
                        <a href="{{ route('login') }}"
                           class="rounded-md border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                            J'ai déjà un compte
                        </a>
                    </div>
                @endguest
            </div>
        </main>

        <footer class="border-t border-gray-200 bg-white">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6 text-center text-xs text-gray-400">
                Guide Touristique — Laravel {{ Illuminate\Foundation\Application::VERSION }}
            </div>
        </footer>
    </div>
</body>
</html>
